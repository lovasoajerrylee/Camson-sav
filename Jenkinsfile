pipeline {
  agent any

  stages {
    stage('Clonage du référentiel') {
      steps {
        checkout scm
      }
    }

    stage('Installation des dépendances') {
      steps {
        sh 'composer install --no-dev --optimize-autoloader'
      }
    }

    stage('Construction du projet') {
      steps {
        sh 'php bin/console cache:clear --env=prod'
        sh 'php bin/console assets:install --env=prod'
      }
    }

    stage('Exécution des tests') {
      steps {
        sh 'php bin/phpunit'
      }
    }

//     stage('Déploiement') {
//       steps {
//         // Placez ici les commandes spécifiques à votre déploiement, telles que le transfert des fichiers sur le serveur distant, l'activation des tâches de maintenance, etc.
//       }
//     }
  }
}
