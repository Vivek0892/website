pipeline {
    agent { label 'jenkins' }
    environment {
        // SonarQube environment variables
        SONAR_SCANNER_HOME = tool 'SonarQubeScanner' // Make sure you have set up the tool in Jenkins
    }
    stages {
        stage('Build') {
            steps {
                // Building a Docker image from the Dockerfile in the repository
                sh 'docker build -t portfolio/demo_portfolio_v1 .'
            }
        }
        stage('SonarQube Analysis') {
            environment {
                // Provide the SonarQube URL and token
                SONAR_TOKEN = credentials('sonarqube-token')
            }
            steps {
                withSonarQubeEnv('SonarQube') { // 'SonarQube' is the name configured in Jenkins
                    // Running SonarQube analysis
                    sh '''
                        ${SONAR_SCANNER_HOME}/bin/sonar-scanner \
                        -Dsonar.projectKey=portfolio \
                        -Dsonar.sources=. \
                        -Dsonar.host.url=http://35.222.230.72:9000 \
                        -Dsonar.login=${SONAR_TOKEN}
                    '''
                }
            }
        }
        stage('Push to Harbor') {
            environment {
                // Referencing Docker credentials stored in Jenkins credential store
                DOCKER_CREDENTIALS = credentials('docker-hub-credentials')
            }
            steps {
                script {
                    // Logging into Harbor Docker registry using secure credentials
                    withCredentials([usernamePassword(credentialsId: 'docker-hub-credentials', passwordVariable: 'DOCKER_CREDENTIALS_PSW', usernameVariable: 'DOCKER_CREDENTIALS_USR')]) {
                        sh "echo $DOCKER_CREDENTIALS_PSW | docker login -u $DOCKER_CREDENTIALS_USR --password-stdin www.tejomayabysivis.in"
                    }

                    // Tagging the Docker image with the build number
                    sh 'docker tag portfolio/demo_portfolio_v1 www.tejomayabysivis.in/portfolio/demo_portfolio:v${BUILD_NUMBER}'
                    
                    // Pushing the tagged Docker image to the Harbor registry
                    sh 'docker push www.tejomayabysivis.in/portfolio/demo_portfolio:v${BUILD_NUMBER}'
                }
            }
        }
        stage('Trigger GitHub Push') {
            steps {
                // Triggering another Jenkins job if required
                build job: 'push_image_tag_portfolio', wait: true, parameters: [string(name: 'Build_Number_Image', value: "${BUILD_NUMBER}")]
            }
        }
    }
    post {
        always {
            // Always archive SonarQube results and print a message
            echo "Pipeline completed!"
        }
    }
}
