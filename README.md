# MyApp1
This is a sample php script written to calculate and display network latency based on the provided CSV data.

## Supported CSV File Structure
> Format: Device From, Device To, Latency (milliseconds)

## Sample CSV Contents
> A,B,10
> 
> A,C,20
> 
> B,D,100
> 
> C,D,30
> 
> D,E,10
> 
> E,F,1000
> 




## Run
This script can be executed directly from the command line provided that the necessary path variables are available. Keyword QUIT can be used to exit the application. 

> git clone https://github.com/sisira70/Test1.git
> 
> cd Test1
> 
> php myApp.php  AbsolutePath/to/csv file  
> 
 Expected user input string contains three parameters.
 1. From Device
 2. To Device
 3. Time Taken to travel between the two devices (milliseconds)
 
 Sample user Input:
 > A D 100
 
 Sample Output:
 > A => C => D => 50 


