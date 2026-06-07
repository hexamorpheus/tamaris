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
