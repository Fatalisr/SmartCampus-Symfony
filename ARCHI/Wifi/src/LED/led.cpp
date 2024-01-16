#include <Arduino.h>
#include <WS2812FX.h>

#include "led.h"
#include "../variables.h"

/*-----------------------------------------------------------------*/
/*                           Variables                             */
/*-----------------------------------------------------------------*/

#define LED_COUNT 13
#define LED_PIN 18
WS2812FX ws2812fx = WS2812FX(LED_COUNT, LED_PIN, NEO_RGB + NEO_KHZ800);



/*-----------------------------------------------------------------*/
/*                           Fonctions                             */
/*-----------------------------------------------------------------*/

/*
* Initialisation de la led
*/
void initLed(){
    ws2812fx.init();
    ws2812fx.setBrightness(20);
    ws2812fx.setColor(0xa4e308); // La led s'allume en orange pendant l'initialisation générale.
    ws2812fx.setMode(FX_MODE_STATIC);
    ws2812fx.start();
    ws2812fx.service();
}

/*-----------------------------------------------------------------*/
/*                             Tasks                               */
/*-----------------------------------------------------------------*/
void setLedColorTask(void* parameter){
    for(;;){
        if(ledTempOk && ledHumiOk && ledCO2Ok){ // Si la variable globale a été modifié par l'echecs d'une capture, on passe la led en rouge
            ws2812fx.setColor(0xFF0000);
        }else{
            ws2812fx.setColor(0x00FF00);
        }
        ws2812fx.service();
        vTaskDelay( pdMS_TO_TICKS( 1000 ) );
    }
}
