/*-----------------------------------------------------------------*/
/*                            Include                              */
/*-----------------------------------------------------------------*/

#include "gestion_api.h"

/*-----------------------------------------------------------------*/
/*                           Fonctions                             */
/*-----------------------------------------------------------------*/

char * getDate();

template <typename t>
String convertTOJson(t value,String type)
{
    //Envoyer la valeur puis un string qui dit le type de la donnée. 
    //Ex : "temp" ,"hum" ou "co2"
    //Renvoie le json pour la requete http post
    StaticJsonDocument<200> file;
    String content;
    String date = getDate();
    file["nom"] = type;
    file["valeur"] = value;
    file["dateCapture"] = date;
    file["localisation"] = ESPCurrentRoom;
    file["description"] = "";
    file["nomsa"] = "ESP-013";
    serializeJson(file, content);
    return content;
}

// Fonction d'envoi des données a l'API
void sendToApi()
{
    HTTPClient https; // Instance du client https

    // Conversion des données capteurs en string
    String SvalueCO2 = String(ppm,DEC);
    String SvalueHum = String(humidity,DEC);
    SvalueHum = SvalueHum.substring(0,4);
    String SvalueTemp = String(temperature,DEC);
    SvalueTemp = SvalueTemp.substring(0,4);

    // Conversion des string en format JSON
    String contentCO2 = convertTOJson(SvalueCO2,"co2");
    String contentHum = convertTOJson(SvalueHum,"hum");
    String contentTemp = convertTOJson(SvalueTemp,"temp");

    // Variable de connexion à la base de donnée
    String contentType = "application/json";
    String dbname = "sae34bdm1eq1";
    String username = "m1eq1";
    String userpass = "sodqif-vefXym-0cikho";


    // Verification de la connexion wifi
    if(WiFi.status()== WL_CONNECTED)
    {
        // Envoie du CO2
        Serial.println("Sending POST request CO2");
        https.begin("https://sae34.k8s.iut-larochelle.fr/api/captures");
        https.addHeader("accept",contentType);
        https.addHeader("dbname",dbname);
        https.addHeader("username",username);
        https.addHeader("userpass",userpass);
        https.addHeader("Content-Type", contentType);
        int httpResponseCode1 = https.POST(contentCO2);
        Serial.println(https.errorToString(httpResponseCode1));

        // Envoie de l'humidité
        Serial.println("Sending POST request Hum");
        https.begin("https://sae34.k8s.iut-larochelle.fr/api/captures");
        https.addHeader("accept",contentType);
        https.addHeader("dbname",dbname);
        https.addHeader("username",username);
        https.addHeader("userpass",userpass);
        https.addHeader("Content-Type", contentType);
        int httpResponseCode2 = https.POST(contentHum);
        Serial.println(https.errorToString(httpResponseCode2));

        // Envoie de la temperature
        Serial.println("Sending POST request Temp");
        https.begin("https://sae34.k8s.iut-larochelle.fr/api/captures");
        https.addHeader("accept",contentType);
        https.addHeader("dbname",dbname);
        https.addHeader("username",username);
        https.addHeader("userpass",userpass);
        https.addHeader("Content-Type", contentType);
        int httpResponseCode3 = https.POST(contentTemp);
        Serial.println(https.errorToString(httpResponseCode3));

    }
    delay(1000);
}

/*-----------------------------------------------------------------*/
/*                             Tasks                               */
/*-----------------------------------------------------------------*/

// Capture les valeurs et incremente les variables globales correspondante
void sendToAPITask(void *parameter){
    vTaskDelay(pdMS_TO_TICKS( 15000 )); 
    for(;;){
        sendToApi();
        vTaskDelay(pdMS_TO_TICKS( APIDelay )); 
    }
}