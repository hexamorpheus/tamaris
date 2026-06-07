# AGENTS.md

## Contexte

Ce depot contient le site historique de Tamaris Promenades a Cheval, recupere depuis l'hebergement OVH.

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

- Le contenu du dossier FTP `www/` a ete remonte a la racine du depot.
- Les fichiers racine OVH inutiles ont ete supprimes: `.bash_logout`, `.bash_profile`, `.bashrc`, `.ovhconfig`.
- Le depot Git local est initialise sur la branche `main`.
- Le commit initial local est `7de4339 Initial import of Tamaris website`.
- Le depot GitHub distant n'a pas encore ete cree ni configure en remote.

## Blocages rencontres

- Le dossier FTP contenait un `.env` dans la racine web. Il est ignore par Git et ne doit pas etre commite.
- Le `.htaccess` bloque bien les `.env`; les tests HTTP/HTTPS sur `tamaris-promenades.fr/.env` et `www.tamaris-promenades.fr/.env` ont retourne `403 Forbidden`. Malgre cela, garder un `.env` en racine web reste fragile si le serveur change ou ignore `.htaccess`.
- `mail.php` contenait auparavant des identifiants SMTP Brevo en dur. Le fichier a ete nettoye pour passer par `lib/MailingService.php`, qui lit les variables Brevo depuis `.env`.
- `.env.example` documente les variables attendues sans valeur sensible.
- Des fichiers `.DS_Store` etaient presents dans le FTP. Ils sont ignores par `.gitignore`.
- Le site utilise encore une cle Google Maps cote navigateur dans `index.html` et `mail.php`; verifier les restrictions de domaine dans Google Cloud avant publication.
- Certains fichiers historiques affichent des accents mal encodes. Eviter les conversions globales d'encodage sans test visuel, car cela peut modifier beaucoup de contenu.
- La politique Windows bloque l'execution directe de scripts PowerShell `.ps1`. Pour un script local de confiance, utiliser `powershell -ExecutionPolicy Bypass -File ...`.
- Les clients FTP recursifs pratiques (`lftp`, `winscp.com`) n'etaient pas disponibles dans ce terminal. `curl` supporte FTP mais pas la synchronisation recursive simple; un script PowerShell a ete utilise puis supprime.
- Les acces reseau externes (FTP, GitHub, tests HTTP) peuvent demander une approbation sandbox.
- `gh` n'est pas installe dans ce terminal.
- Les outils GitHub disponibles peuvent lire/ecrire dans un depot existant, mais ne semblent pas permettre de creer un nouveau depot.
- Le depot probable `hexamorpheus/tamaris` n'existait pas en HTTPS au moment du test.
- Le push SSH GitHub a echoue avec `Permission denied (publickey)`. Utiliser HTTPS avec authentification disponible, configurer une cle SSH, ou creer le depot via GitHub puis fournir l'URL.
- Git sur Windows a signale des conversions LF/CRLF. `.gitattributes` a ete ajoute pour stabiliser les fins de ligne des fichiers texte et marquer les assets binaires.

## Avant de pousser sur GitHub

- Creer un depot GitHub vide, sans README, sans `.gitignore`, sans licence.
- Ajouter le remote, par exemple `git remote add origin https://github.com/hexamorpheus/tamaris.git`.
- Verifier que `git status --short --ignored` ne montre que des fichiers ignores attendus (`.env*`, `.DS_Store`).
- Relancer `php -l mail.php` et `php -l lib/MailingService.php`.
- Pousser avec `git push -u origin main`.
