# Verif marionnet
## Idée
examen de TP/évalutation automatique TPS marionnet.

## Format du fichier bareme.json
* info : est affiché en bleu et n'est pas compté dans les notes
* good : est affiché en vert
* mandatoryGood : n'est pas affiché par défault 
* partial : est affiché en orange
* wrong : est affiché en rouge
* wandatoryWrong : est affiché en rouge et intéromp l'évaluation indépendamment de la variable tolerance
```json
{
    "tolerance" : 0,
    "requests" : [
        {
            "label" : "nom du test",
            "command" : "commande à executer",
            "responses" : [
                {"regex" : "regex reponse attendu", "comment" : "message à afficher si la réponse est bonne","pts" : 2, "type" : "good"},
                {"equal" : "reponse attendu", "comment" : "message à afficher si la réponse est bonne","pts" : 1, "type" : "partial"},
                {"default" : "", "comment" : "message par défaut si aucune n'est validé","pts" : 0, "type" : "wrong"}
            ],
            "bareme" : 2
        }
    ]
}
```
* exemple
```json
{
    "tolerance" : 0,
    "requests" : [
        {
            "label" : "ip m2 eth0",
            "command" : "getIPAddress m2",
            "responses" : [
                {"regex" : "11\\.0\\.1\\..*", "comment" : "oui très bien","pts" : 1, "type" : "info"},
                {"default" : "", "comment" : "l'ip n'est pas bon, regarde avec ifconfig","pts" : 0, "type" : "info"}
            ],
            "bareme" : 1
        },
        {
            "label" : "mask m2 eth0",
            "command" : "getMask m2",
            "responses" : [
                {"equal" : "255.255.255.0", "comment" : "oui très bien", "pts" : 1 , "type" : "good"},
                {"default" : "", "comment" : "le mask n'est pas bon, regarde avec ifconfig", "pts" : 0, "type" : "wrong"}
            ],
            "bareme" : 1
        },
        {
            "label" : "cable S1aM1",
            "command" : "getCable S1 m1",
            "responses" : [
                {"regex" : "direct", "comment" : "oui très bien", "pts" : 1, "type" : "good"},
                {"regex" : "cross", "comment" : "il y a un cable mais il n'est pas du bon type", "pts" : 0.5, "type" : "partial"},
                {"default" : "", "comment" : "il manque un cable", "pts" : 0, "type" : "wrong"}
            ],
            "bareme" : 1
        }
    ]
}
```

## Format du fichier requete.json
Le fichier requête json conserve juste le label et la command de bareme.json et sert juste à être utilisé par le client
```json
[
    {
        "label" : "nom du test",
        "command" : "commande à executer",
    }
]
```
* exemple
```json
[
    {
        "label" : "ip m2 eth0",
        "command" : "getIPAddress m2"
    },
    {
        "label" : "mask m2 eth0",
        "command" : "getMask m2"
    },
    {
        "label" : "cable S1aM1",
        "command" : "getCable S1 m1"
    }
]
```

## Commande/Fonction utilisable dans le fichier de requetes
### marioSsh
Exécute une commande via ssh et donne un retour
* syntax
```bash
marioSsh <machine> <cmd>
```
* exemple
```bash
marioSsh m1 ls -la
```
### cidr2mask
Convertie un mask cidr en mask decimal pointé
* syntax
```bash
cidr2mask <cidr>
```
* exemple
```bash
cidr2mask 24
```
### testPresence
Test la presence de n'importe quel équipements (affiche "ok" si il exist)
* syntax
```bash
testPresence <machine>
```
* exemple
```bash
testPresence S1
```
### getIPAddress
Affiche l'ip de la machine (par défaut : interface=eth0)
* syntax
```bash
getIPAddress <machine> [interface]
```
* exemple
```bash
getIPAddress m1
getIPAddress m1 eth1
```
### getMask
Affiche le mask (decimal pointé) de la machine (par défaut : interface=eth0)
* syntax
```bash
getMask <machine> [interface]
```
* exemple
```bash
getMask m1
```
### getCidrMask
Affiche le mask (cidr) de la machine (par défaut : interface=eth0)
* syntax
```bash
getCidrMask <machine> [interface]
```
* exemple
```bash
getCidrMask m1
```
### getFullIP 
Affiche l'ip et le mask cidr de machine (par défaut : interface=eth0)
* syntax
```bash
getFullIP <machine> [interface]
```
* exemple
```bash
getFullIP m1
```
* format de l'affichage
```
10.0.0.1/24
```
### getRoute
Affiche la route de la machine
* syntax
```bash
getRoute <machine> <route>
```
* exemple
```bash
getRoute m1 default
getRoute R1 11.0.1.0
```
* format de l'affichage
```
0.0.0.0 10.0.0.254 0.0.0.0 UG 0 0 0 eth0 <- pour default
```
### getNet
Affiche l'adresse réseaux de la machine (par défaut : interface=eth0)
* syntax
```bash
getNet <machine> [interface]
```
* exemple
```bash
getNet m1
```
### pingMachine
Ping d'une machine à une autre (affiche "ok" si il y parvient) (par défaut : interface=eth0)
* syntax
```bash
pingMachine <machine1> <machine2> [interface]
```
* exemple
```bash
pingMachine m1 m2
```
### getCable
Affiche le type (cross ou direct) de cable entre 2 équipements
* syntax
```bash
getCable <machine1> <machine2>
```
* exemple
```bash
getCable R1 S1
```
* format de l'affichage
```
direct
```
### checkIP
Vérifie l'ip d'une machine en fonction du mask (affiche "ok" si il y parvient) (par défaut : interface=eth0)
* syntax
```bash
checkIP <machine> <net> [interface]
```
* exemple
```bash
checkIP R1 10.0.0.0
```

## Scenario
* Un ps (script shell appelé verifMario) doit être lancé par l'utilisateur :
    * vérif des besoins et installations des éléments manquant : zenity par exemple
    * ce script demande le nom, prénom, id de l'examen (faut-il le code NIP ?)
    * ce script vérifie que marionnet-daemon est lancé, le lance au besoin (utile ?)
    * récupere par wget l'environnement d'examen (le .mar initial si dispo ?)
    * puis lance marionnet sur ce fichier s'il existe
    * se met en batch, dort un peu (ou pas ?)
    * hyp1 : récupère les commandes à lancer périodiquement sur les Machines Virtuelles
    * hyp2 : sur demande de l'utilisateur récupère les cmdes à lancer sur les MV
    * en cas de mode examen : démarre le chrono quand les MV ont démarré (trop stressant ?)

    * Une fois les commandes récupérées, se connecte aux machines demandées par ssh, 
        lance les commandes et envoie les résultats sur un serveur de sauvegarde
        * le serveur de sauvegarde doit garder une trace de toutes les sauvegardes en cas de plantage etc.
        * en fonction de ce qui lui est envoyé, le serveur de sauvegarde doit envoyer une note/20 
        (ou un score en % ?)
    * éventuelllement affiche le temps restant par zenity et la note atteinte 
        (peut être la cacher le premier tiers temps ?)
    * si temps restant =0 annonce arrêt de l'examen (mais ce sera à l'util. d'arreter les MV et de tenter de sauver le projet) 

* Sur le serveur de sauvegarde, pour le calcul de la note
    * les commandes initiales, fichier .mar initial, un répertoire par étudiant ? 
      (pour permettre des examens différentiés ? au pire des liens symb vers le même rep.)
    * liste de scripts à exécuter avec répertoire des résultats attendus et coeff par résultat
    * ces résultats attendus doivent pouvoir être créés par les mêmes scripts
    * comparaison des résultas vs résultats attendus par la commande  diff :
        * si identiques : cumul du coeff, sinon 0 (donc prévoir des tests fins)
    * idéal: graphe de dépendance des tests : inutile de faire tel test si dépendances non satisfaites
    * sinon ordre des tests : dès qu'un test ne fonctionne pas arrêt ?
        Fichier de description des tests :
            nom-test; hostname ; coeff ; dépendances ?
        dans le répertoire scripts le nom-test, répertoire expected, etc

* partir d'un cas (sujet de TP de S2 par ex.) : voir ce que l'on peut vérifier etc.
    Le sujet de TP devra être adapté: très directif.
    Aux questions d'observations on peut demander de remplir un fichier de réponses sur une MV 
    (ou dans un fichier texte sur le bureau ? Pb : être très directif sur le format!).

* Il existait dans les archives de marionnet une version exam, il faudrait enquêter sur ce qui
était déjà fait ou pas.