#include<stdio.h>
#include<unistd.h>
#include<stdlib.h>
int main(){

	while(1){
		system("php artisan schedule:run");
		sleep(1000);
	}
	return 0;
}
