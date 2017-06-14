/*
    Things Control. Control anything you want.
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

#include <wiringPi.h> // Include WiringPi library!

const int thingsOut = 17;

int main(int argc, char *argv[])
{
	int DELAY = 1000;
/*
	while(argc--) {
                printf("argc %d, argv %s\n", argc, *argv++);
	}
*/	
	if (argc < 1) {
		printf("Please input delay time (1-59 min.)\n");
		exit(1);
	}

	if (atoi(argv[1] < 60) {
		DELAY = (int) 1000 * 60 * ato(argv[1]); 
	}
	else {
		DELAY = (int)  1000 * atoi(argv[1]);
	}
	printf("Delay %d, %s\n", DELAY, argv[1]);
 
	wiringPiSetupGpio(); // Initialize wiringPi -- using Broadcom pin numbers
	pinMode(thingsOut, OUTPUT);

	printf("On\n");
	digitalWrite(thingsOut, HIGH);
	update_status("ON");
	delay(DELAY);
	printf("Off\n");
	digitalWrite(thingsOut, LOW);
	update_status("OFF");
	
	return 0;
}
