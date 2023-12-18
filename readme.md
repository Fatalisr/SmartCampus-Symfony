## Membres de l'équipe 
- JOYET Gabin (PO)
- GAY Ulysse (SM)
- MOREAU Romain
- BEAUFORT Alex
- BLANDIN Mathieu

## Support
- Hha (GPI)
- JmBo(ARCHI)
- (DEV)

[Lien vers le Miro](https://miro.com/app/board/uXjVNeCX4Lg=/)
[Lien vers les maquettes interactives](https://app.uizard.io/p/d68e54e8/preview)

- Démarrer la stack
```
docker compose up --build
```
- Inspecter l'état des services
```
docker compose ps
```
 - Connection au conteneur associé su service `sfapp`
```bash
docker compose exec sfapp bash
```
- Après connexion, on doit être dans `/app`, vérifier
```
pwd
```
- Regénérer le vendor
```
composer dump-autoload
```
```
composer install
```
- Vérifier l'exécution du service `sfapp`
```
localhost:8000
```
 /!\ Attention à bien être sur la branche DEV
