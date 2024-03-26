/*-----------------------------------------------------------------*/
/*                            Include                              */
/*-----------------------------------------------------------------*/

// Fichiers du projet
#include "variables.h"

/*-----------------------------------------------------------------*/
/*                         Setup et loop                           */
/*-----------------------------------------------------------------*/

void setup()
{
    Serial.begin(9600); // Vitesse du moniteur

    // Connection au wifi 
    connectedToWifi();

    // Initialisation des differents composants
    initLed(); 
    initClock();
    initCO2Sensor();
    initTempHumSensor();
    init_screen();

    Serial.println("End of Setup");

    /*-----------------------------------------------------------------*/
    /*                               TASKS                             */
    /*-----------------------------------------------------------------*/
    xTaskCreate(setLedColorTask, "couleur de led", 10000, NULL, ledTaskPriority, NULL);
    xTaskCreate(getCO2Task, "capture du CO2", 10000, NULL,CO2TaskPriority, NULL);
    xTaskCreate(getHumTempTask, "capture temp et humi", 10000, NULL, TempHumiTaskPriority, NULL);
    xTaskCreate(sendToAPITask, "envoie à l'api", 10000, NULL, APITaskPriority, NULL);
    xTaskCreate(displayValuesOnScreenTask, "affichage a l'écran", 10000,NULL, screenTaskPriority, NULL);
}

void loop()
{
    //getCO2Value(ppm);
    //getHumTempvalue(humidity, temperature);
    //getDate();
}