// pipeline for docker compose up -d
pipeline {
    agent any
    stages {
        stage('Remove old containers') {
            steps {
                sh 'docker-compose down'
            }
        }
        
        stage('Build') {
            steps {
                sh 'docker-compose up -d'
            }
        }
    }
}
