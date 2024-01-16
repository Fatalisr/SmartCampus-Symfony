#ifndef ECRAN_H
#define ECRAN_H

#include <Arduino.h>
#include <U8g2lib.h>
#include <string>


using namespace std;

void init_screen();
void displayScreen(int x, int y, String data);
void displaySensorValue(int x, int y, String dataType, String dataUnit, float value);
void loadingDisplay(int x, int y);

void displayValuesOnScreenTask(void* parameter);

#endif