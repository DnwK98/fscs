pipeline {
    agent any
    options {
        skipStagesAfterUnstable()
    }
    stages {
        stage('Test') {
            steps {
                echo 'Testing...'
                sh 'bin/test.sh'
            }
        }
    }
}
