# AGENTS.md

## Contexte

Ce depot contient le site historique de Tamaris Promenades a Cheval.

Le site est principalement statique, avec quelques fichiers PHP pour les formulaires et l'envoi d'emails.

## Regles de travail

- Ne jamais commiter les fichiers d'environnement ou d'acces locaux (`.env`, `.env.local`, `.env.*.local`).
- Eviter les refontes larges sans demande explicite: privilegier des changements courts, lisibles et faciles a deployer.
- Garder les chemins d'assets existants (`css/`, `js/`, `img/`, `fonts/`) sauf necessite.
- Verifier les changements de securite avant publication, notamment les fichiers exposes a la racine web.

## Verification rapide

- La page d'accueil est `index.html`.
- Les fichiers sensibles doivent rester ignores par Git.
- Avant un push public, verifier qu'aucun secret n'est present dans les fichiers suivis.

## Etat actuel

- Le depot Git local est initialise sur la branche `main`.
- Le commit initial local est `7de4339 Initial import of Tamaris website`.
- Le depot GitHub distant est `https://github.com/hexamorpheus/tamaris.git`.
- Le deploiement doit utiliser les parametres locaux ignores par Git, notamment `.env.deploy.local`.

## Blocages rencontres

- Le dossier du site contient un `.env` local. Il est ignore par Git et ne doit pas etre commite.
- Le `.htaccess` bloque les `.env`; garder un `.env` en racine web reste fragile si le serveur change ou ignore `.htaccess`.
- `mail.php` contenait auparavant des identifiants SMTP Brevo en dur. Le fichier a ete nettoye pour passer par `lib/MailingService.php`, qui lit les variables Brevo depuis `.env`.
- `.env.example` documente les variables attendues sans valeur sensible.
- Des fichiers `.DS_Store` etaient presents dans l'import initial. Ils sont ignores par `.gitignore`.
- Le site utilise encore une cle Google Maps cote navigateur dans `index.html` et `mail.php`; verifier les restrictions de domaine dans Google Cloud avant publication.
- Certains fichiers historiques affichent des accents mal encodes. Eviter les conversions globales d'encodage sans test visuel, car cela peut modifier beaucoup de contenu.
- La politique Windows bloque l'execution directe de scripts PowerShell `.ps1`. Pour un script local de confiance, utiliser `powershell -ExecutionPolicy Bypass -File ...`.
- Les acces reseau externes (deploiement, GitHub, tests HTTP) peuvent demander une approbation sandbox.
- `gh` n'est pas installe dans ce terminal.
- Les outils GitHub disponibles peuvent lire/ecrire dans un depot existant, mais ne semblent pas permettre de creer un nouveau depot.
- Le depot GitHub `hexamorpheus/tamaris` existe et `main` suit `origin/main`.
- Git sur Windows a signale des conversions LF/CRLF. `.gitattributes` a ete ajoute pour stabiliser les fins de ligne des fichiers texte et marquer les assets binaires.

## Avant de pousser ou deployer

- Verifier que `git status --short --ignored` ne montre que des fichiers ignores attendus (`.env*`, `.DS_Store`).
- Relancer `php -l mail.php` et `php -l lib/MailingService.php`.
- Pousser avec `git push`.
- Deployer avec les variables locales de `.env.deploy.local`, sans commiter ce fichier.
