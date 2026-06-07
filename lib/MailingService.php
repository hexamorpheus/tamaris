<?php

/**
 * @class MailingService
 * @package App\services\Utils
 * @description : service to send mail with the Brevo API
 * Will send mail to the current user if the environment is not production
 * else will send mail to the specified recipient
 */
class MailingService
{
    /** @var bool set to true if in production env*/
    protected $isProductionEnv = false;

    /** @var string dev mail loaded if not production env*/
    protected $devMail;

    /** @var string DEFAULT_SENDER_NAME The default sender name */
    const DEFAULT_SENDER_NAME = "Tamaris";

    /** @var string DEFAULT_SENDER_MAIL The default sender mail */
    const DEFAULT_SENDER_MAIL = "info@tamaris-promenades.fr";


    /** @var string DEFAULT_MAIL The default mail used if the sender is not from our domain */
    const DEFAULT_MAIL = "info@tamaris-promenades.fr";

    /**
     * @description : constructor, check if the environment is production
     * if not then override the recipent and cc/bcc with the dev mail
     */
    public function __construct()
    {
        $this->loadEnvFile(dirname(__DIR__) . '/.env');

        // $current is the current page
        // used to make forgottent password work in dev env
        $current = basename($_SERVER['PHP_SELF']);
        if ($this->getEnvValue('ENV') == 'PROD' || $current == 'login.php') {
            $this->isProductionEnv = true;
        } else {
            $this->devMail = 'remi.cervilla@adhera.ca';
        }
    }

    /**
     * Loads a simple KEY=VALUE environment file without exposing it through Git.
     */
    private function loadEnvFile($path)
    {
        if (!is_readable($path)) {
            return;
        }

        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
                continue;
            }

            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            if ($key === '') {
                continue;
            }

            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }

            $_ENV[$key] = $value;
            putenv($key . '=' . $value);
        }
    }

    private function getEnvValue($key, $default = '')
    {
        if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
            return $_ENV[$key];
        }

        $value = getenv($key);
        if ($value !== false && $value !== '') {
            return $value;
        }

        return $default;
    }

    /**
     * @description : check data by key before sending the mail
     * @param $data : array
     * @param $key : key to check
     * @return bool
     */
    private function checkDatas($data, $key)
    {
        if (!isset($data[$key]) || $data[$key] === "") {
            return false;
        }
        return true;
    }

    /**
     * @description : check if the attachement is correctly set
     * @param $data
     * @return bool
     */
    private function checkAttachmentFormat($data)
    {
        foreach ($data["attachment"] as $key => $value) {
            if (!isset($data["attachment"][$key]["name"]) || $data["attachment"][$key]["name"] === "") {
                return false;
            }
            if (!isset($data["attachment"][$key]["content"]) || $data["attachment"][$key]["content"] === "") {
                return false;
            }
        }
        return true;
    }

    /**
     * @description : check the domain of the sender has the right domain
     * (BREVO_API_DMARC=boulangerie-ange.fr)
     * @param $data
     * @return bool
     */
    private function checkDomainIsCorpo($data)
    {
        $sApiDMARC = $this->getEnvValue('BREVO_API_DMARC');
        $sExplodedMail = explode("@", $data['sender']['email']);
        if (count($sExplodedMail) === 2 && strcasecmp($sExplodedMail[1], $sApiDMARC) === 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @description : check if the sender entry is correctly set
     * if not, fill it with default values, manage the case that the
     * mail is sent outside the defined domain
     * @param $data
     * @return array
     */
    private function fillSenderAndReplyTo($data)
    {
        $aResult = array();

        if ((!isset($data["sender"]) || $data["sender"] === "")) {
            $aResult['sender']['name'] = self::DEFAULT_SENDER_NAME;
            $aResult['sender']['email'] = self::DEFAULT_SENDER_MAIL;
        } else {
            if ($this->checkDomainIsCorpo($data)) {
                $aResult['sender'] = array(
                    "name" => $data["sender"]['name'],
                    "email" => $data["sender"]['email']
                );
            } else {
                $aResult['sender'] =  array(
                    "name" => isset($data["sender"]['name']) ? $data["sender"]['name'] : self::DEFAULT_SENDER_NAME,
                    "email" => self::DEFAULT_MAIL
                );
//                $aResult['replyTo'] = array(
//                    "name" => $data["sender"]['name'],
//                    "email" => $data["sender"]['email']
//                );
            }
        }

        if (isset($data['replyTo']['email']) && filter_var($data['replyTo']['email'], FILTER_VALIDATE_EMAIL)) {
            $aResult['replyTo'] = array(
                "name" => isset($data['replyTo']['name']) ? $data['replyTo']['name'] : '',
                "email" => $data['replyTo']['email']
            );
        }

        return $aResult;
    }

    /**
     * @description : Explodes the CC or BCC string of an email into an array of email addresses.
     * @param array $data   An array which includes the CC or BCC string of an email.
     * @param bool  $isBCC  A boolean value which is used to decide if the operation
     * should be performed for BCC (true) or CC (false). Default is false.
     * @return array        An array of arrays, where each sub-array has an "email" key
     * with the corresponding email address as its value.
     */
    private function explodeMailCC($data, $isBCC = false)
    {
        $result = array();
        $filter = $isBCC ? "bcc" : "cc";
        $sExplodedMail = explode($filter . ": ", strtolower($data[$filter]));
        foreach ($sExplodedMail as $key => $value) {
            if ($key === 0) {
                continue;
            }
            $result[$key - 1] = array(
                "email" => trim(preg_replace('/\s\s+/', ' ', $value))
            );
        }
        return $result;
    }

    /**
     * @description : format the sender information for the API
     * if the environment is not production, the sender will be the current user mail
     * @param $data : array
     * @return array
     */
    private function formatRecipientArray($data)
    {
        if (!$this->isProductionEnv) {
            return array(
                "email" => $this->devMail,
                "name" => "Dev Mail"
            );
        } else {
            $sExplodedMail = explode("@", $data['recipient']);
            return array(
                "email" => $data['recipient'],
                "name" => $sExplodedMail[0]
            );
        }
    }

    public function sendMultipleMail($data){
        $result = array();
        $resultsAreOk = true;
        $errorMessage = "";
        foreach ($data['messages'] as $key => $value) {
            $dataCopy = $data;
            $dataCopy['recipient'] = $key;
            $dataCopy['content'] = $value['message'];
            $dataCopy['subject'] = $value['subject'];
            $result = json_decode($this->sendMail($dataCopy), true);
            if($result['res'] === 'nok'){
                $resultsAreOk = false;
                $errorMessage = "Une erreur est survenue lors de l'envoi du mail à ".$key." : ".$result['error'];
                break;
            }
        }
        if($resultsAreOk){
            return json_encode(['res' => 'ok', 'response' => $result]);
        }else{
            return json_encode(['res' => 'nok', 'error' => $errorMessage]);
        }
    }

    /**
     * @description : send a mail with the Brevo API
     * @param $data
     * @param string|null $cc
     * $data [subject] required => string (mail subject)
     * $data [content] required => string (mail content in html)
     * $data [recipient] required => string (email) will be split at @ to get the name
     * $data [sender] => Array (name[optionnal], email) if empty will be filled with default values
     * $data [cc] => - Array (name[optionnal], email) recipient in copy
     *               - String thet will be exploded at "cc: " to get the mail
     * $data [bcc] => - Array (name[optionnal], email) recipient in copy hidden
     *                - String thet will be exploded at "bcc: " to get the mail
     * $data [attachment] => Array (name, content(base64 encoded file)) file attached to the mail
     * @return string
     * @throws Exception
     */
    public function sendMail($data, $cc = null)
    {
        $sApiKey = $this->getEnvValue('BREVO_API_KEY');
        $sApiUrl = rtrim($this->getEnvValue('BREVO_API_URL'), '/') . '/smtp/email';

        if ($sApiKey === '' || $sApiUrl === '/smtp/email') {
            throw new Exception('Configuration Brevo manquante.');
        }

        try {
            for ($i = 0; $i < 2; $i++) {
                if ($i === 1 && $cc === null) {
                    break;
                } elseif ($i === 1 && $cc !== null) {
                    unset($data['sender']);
                    $data['sender']['email'] = $cc;
                    $data['recipient'] = $cc;
                }

                $aSenderDatas = $this->fillSenderAndReplyTo($data);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_URL, $sApiUrl);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'accept: application/json',
                    'api-key:' . $sApiKey ,
                    'content-type: application/json'
                ));
                //If recipient is not set, throw an exception
                if (!$this->checkDatas($data, 'recipient')) {
                    echo "Aucun destinataire n'a été spécifié pour l'envoi du mail.";
                }
                //If subject is not set, throw an exception
                if (!$this->checkDatas($data, 'subject')) {
                    echo "Aucun sujet n'a été spécifié pour l'envoi du mail.";
                }
                //If content is not set, throw an exception
                if (!$this->checkDatas($data, 'content')) {
                    echo "Aucun contenu n'a été spécifié pour l'envoi du mail.";
                }

                $sPayload = array(
                    "to" => array($this->formatRecipientArray($data)),
                    "subject" => $data['subject'],
                    "htmlContent" => $data['content'],
                );

                $sPayload = array_merge($sPayload, $aSenderDatas);

                //If attachment is set, check if it's correctly formated
                if ($this->checkDatas($data, 'attachment')) {
                    if (!$this->checkAttachmentFormat($data)) {
                        echo "La pièce jointe n'est pas correctement formattée pour l'envoi du mail.";
                    } else {
                        $sPayload["attachment"] = $data['attachment'];
                    }
                }

                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sPayload));
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

                $response = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo curl_error($ch);
                    die();
                }
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($http_code != 201 && $http_code != 202 && $http_code != 200) {
                    return json_encode(['res' => 'nok', 'error' => 'Mail non envoye, code HTTP : ' . $http_code, 'response' => $response]);
                    echo "Mail non envoyé, error code : " . $http_code;
                }
            }
        } catch (Exception $th) {
            return json_encode(['res' => 'nok', 'error' => $th->getMessage()]);
        } finally {
            if (isset($ch)) {
                curl_close($ch);
            }
        }
        return json_encode(['res' => 'ok', 'response' => $response, 'http_code' => $http_code]);
    }
}
