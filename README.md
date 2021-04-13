# Verif marionnet
## Idée
examen de TP/évalutation automatique TPS marionnet.

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