#ifndef LED_H
#define LED_H

#include <Arduino.h>
#include <WS2812FX.h>



void initLed();
void setLedColorTask(void* parameter);

#endif