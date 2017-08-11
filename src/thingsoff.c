/*
    Things Control. Control anything as you want.
    Copyright (C) 2017  Pornthep Nivatyakul

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

    ThingsControl  Copyright (C) 2017  Pornthep Nivatyakul, kaebmoo@gmail.com, seal@ogonan.com
    This program comes with ABSOLUTELY NO WARRANTY;
    This is free software, and you are welcome to redistribute it
    under certain conditions.


*/

#include <curl/curl.h>


#include <fcntl.h>
#ifdef WIN32
#include <io.h>
#else
#include <unistd.h>
#endif
#include <sys/types.h>
#include <sys/stat.h>


#if LIBCURL_VERSION_NUM < 0x070c03
#error "upgrade your libcurl to no less than 7.12.3"
#endif

#include <stdio.h>    // Used for printf() statements
#include <string.h>
#include <stdlib.h>
#include <time.h>

#include <wiringPi.h> // Include WiringPi library!

#include "thingscontrol.h"



int main(int argc, char *argv[])
{
	int output = 0;
	int port;

	if (argc < 2) {
		printf("Please provide relay (output) number. Example: %s 0\n", argv[0]);
		exit(1);
	}

	output = abs(atoi(argv[1]));
	if (output > 3) {
		output = 0;
	}
	port = output;
	
	wiringPiSetupGpio(); // Initialize wiringPi -- using Broadcom pin numbers
	pinMode(thingsOut, OUTPUT);
	pinMode(thingsOut1, OUTPUT);
	pinMode(thingsOut2, OUTPUT);
	pinMode(thingsOut3, OUTPUT);


	printf("Off\t");
	switch (output) {
		case 0 :
			digitalWrite(thingsOut, LOW);
			break;
		case 1 :
			digitalWrite(thingsOut1, LOW);
			break;
		case 2 :
			digitalWrite(thingsOut2, LOW);
			break;
		case 3 :
			digitalWrite(thingsOut3, LOW);
			break;
	}

	currenttime();
	status_update("OFF", port);

	return 0;
}

int currenttime()
{
	time_t     now;
	struct tm  ts;
	char       buf[80];

	// Get current time
	time(&now);
	// Format time, "ddd yyyy-mm-dd hh:mm:ss zzz"
	ts = *localtime(&now);
	strftime(buf, sizeof(buf), "%a %Y-%m-%d %H:%M:%S %Z", &ts);
	printf("%s\t", buf);

	return 0;
}
