cc -lwiringPi -lcurl -lconfig -ljson-c thingson.c status-update.c -o ../bin/thingson
cc -lwiringPi -lcurl -lconfig -ljson-c thingsoff.c status-update.c -o ../bin/thingsoff
cc -lwiringPi -lcurl -lconfig -ljson-c thingsontimer.c status-update.c -o ../bin/thingsontimer
cc -lwiringPi -lcurl -lconfig -ljson-c thingsonoff.c status-update.c -o ../bin/thingsonoff
cc -lwiringPi -lcurl -lconfig -ljson-c update.netpie.status.c -o ../bin/update.netpie.status
