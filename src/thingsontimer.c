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

const int thingsOut = 16; /* automation phat BCM 16 GPIO 27 physical 36 */

int status_update(char *status);

int main(int argc, char *argv[])
{
	int DELAY = 1000;
	int min;


	//printf("argc %d\n", argc);
	if (argc < 2) {
		printf("Please input delay time (1-59 min.)\n");
		exit(1);
	}

	min = atoi(argv[1]);

	if (min == 0) {
		printf("On timer = 0, exit\n");
		exit(1);
	}
	else if (min > 1439) {
		min = 1439;
	}
	// millisecond.
	DELAY = (int) 60000 * min;

	if (min < 60) {
		DELAY = (int) 60000 * min;
		printf("Delay %d min, argv1: %s\n", DELAY/60000, argv[1]);
	}
	else {
		DELAY = min;
		printf("Delay %d second, argv1: %s\n", DELAY/1000, argv[1]);
	}

	wiringPiSetupGpio(); // Initialize wiringPi -- using Broadcom pin numbers
	pinMode(thingsOut, OUTPUT);

	printf("On\t");
	digitalWrite(thingsOut, HIGH);
	currenttime();
	status_update("ON");


	delay(DELAY);
	printf("Off\t");
	digitalWrite(thingsOut, LOW);
	currenttime();
	status_update("OFF");


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
