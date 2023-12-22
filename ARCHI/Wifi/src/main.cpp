#include <Arduino.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include "esp_wpa2.h" //Librairie wpa2 pour la connexion au réseaux d'enterprise

#define EAP_IDENTITY "ugay" //Login
#define EAP_USERNAME "ugay" //Login
#define EAP_PASSWORD "LaceulalTelephone7!" //Eduroam password
const char* ssid = "eduroam"; // Eduroam SSID

const String serverName = "https://sae34.k8s.iut-larochelle.fr/api/captures"; //URL de l'api pour l'envoie de données
int counter;//Compteur pour la connexion
unsigned long lastTime = 0;//Compteur pour le délai entre chaque envoie
unsigned long timerDelay = 15000;//Délai de 30 secondes

// Fonction pour transformer la donnée en Json
template <typename t>
String convertTOJson(t value, String type);
void setup()
{
    Serial.begin(9600);
    WiFi.begin(ssid, WPA2_AUTH_PEAP, EAP_IDENTITY, EAP_USERNAME, EAP_PASSWORD);
    Serial.println("Tentative de Connexion");
    while (WiFi.status() != WL_CONNECTED)
    {
        delay(500);
        Serial.print(".");
        counter++;
        if(counter>=60)
        {
            Serial.println("Restart");
            ESP.restart();
        }
    }
    Serial.println("");
    Serial.print("Connected to WiFi with IP : ");
    Serial.println(WiFi.localIP());
    Serial.println("End of Setup");

    int status = WL_IDLE_STATUS;
}
void loop()
{
    HTTPClient https;
    

    String contentType = "application/json";
    String postData = content;
    String dbname = "sae34bdm1eq1";
    String username = "m1eq1";
    String userpass = "sodqif-vefXym-0cikho";
    //Send an HTTPS POST request every 30 seconds
    if ((millis() - lastTime) > timerDelay)
    {
        //Check WiFi connection status
        if(WiFi.status()== WL_CONNECTED)
        {
            Serial.println("Sending POST request");
            https.begin("https://sae34.k8s.iut-larochelle.fr/api/captures");
            https.addHeader("accept",contentType);
            https.addHeader("dbname",dbname);
            https.addHeader("username",username);
            https.addHeader("userpass",userpass);
            https.addHeader("Content-Type", contentType);
            int httpResponseCode = https.POST(postData);
            Serial.println(https.errorToString(httpResponseCode));
        }
        else
        {
            Serial.println("WiFi Disconnected");
        }
        lastTime = millis();
    }
    delay(1000);
}

template <typename t>
String convertTOJson(t value,String type)
{
    //Envoyer la valeur puis un string pui dit le type de la donnée. ex : "temp" ,"hum" ... et renvoie le json pour la requete http post
    StaticJsonDocument<200> file;
    String content;
    file["nom"] = type;
    file["valeur"] = "19.2";
    file["dateCapture"] = "2023-12-20 08:00:00";
    file["localisation"] = "D004";
    file["description"] = "";
    file["nomsa"] = "13";
    serializeJson(file, content);
    return content;
}