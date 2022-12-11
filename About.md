## Solution thought process

The requirements for this task started by showing me a response from an external service that provides screenshots for requested websites.

After that, I was asked to create an **API endpoint in PHP** *(I chose Laravel)* and a **frontend** in any popular framework/lib, which I decided to use **React JS.**

The first thing I had in mind was *how to get a more realistic / real-world response to use* inside of the application and deal with the caveats of *CORS and HTTP responses*, instead of only building a fake json file on the storage.

I knew about services like mockable.io that can provide a functional API, yet with data provided by yourself.
To mock the service responses, I took screenshot of 4 common casinos/betting websites and *converted those images to base64* on the mockable.io service.

As soon as I had the correct API output, I could start coding the backend part of the solution, making sure I was getting data from a `real-world HTTP` request over the web.

I started by creating the **routes** to hit the **controllers** of the **API**, which I have used the controllers inside of laravel's http's folder, so it won't have any issues, as I have had in previous versions of laravel.

For this test, I did my best to provide as much as possible clues of my knowledge, **using a little bit of every portion of common stuff in a web development**. Like: *API calls, https requests, database, storage, json, error handling and so on.*

Related to the database and the columns created/used, I have used a column called `website` to be the reference for what images / data I already have and which I didn't. I **know** that using a string is not the correct way to garantee the data integrit, but I took this decision **only** for this test, so I didn't need to use relations with other tables and make it simpler to write, test and to show my knowledge.

Talking about the **controller and its methods**, here are the reasons why I wrote them:
* `index()` - Used to retrieve all the screenshot data from database.

* `getScreenshot()` - This one is to retrieve data from the database and handle response or other methods calls to make it work properly.

* `checkImageNeedsUpdate()` - Checks whether the image needs to be updated or not. I have set it to 3 days, as the requirements for this were to have an updated image at least twice a week. So everytime it is called, it checked the "age" of the image. I have used carbon here as this it the standard Date/Time lib inside of Laravel.

* `checkImageExists()` - Checks if a backed up image (.png file) is already on the storage.

* `getCurrentWebsiteFilename()` - Retrieve the current filename for a given website, this way the system can delete the old image, when the new one is written to the storage and save space (thinking about thousands of screenshots overtime).

* `updateScreenshot()` - The main method of the system that drives the calls to the 3rd-party service, do the necessary methods calls.
	* Detailed thoughts on this:
		* It will check if the image already exists and if it needs to be updated based on the last update.
		* If so, it will hold the old filename, save the updated image to disk and if it goes well, update the database and delete the old file. For simplicity, I didn't used statements like try/catch/throw err to make it.
		* If the image doesn't exist yet, it will proceed as the same but not dealing with existent file/data.
		* If the image is there and doesn't need to update, it will return a json with the status key and the value updated.
* `store()` - Saves the screenshot data on our end (database).

* `saveFileDisk()` - it will get the base64 string and decode it to the image itself, while creating the filename. On this method, I have used uuid() to take advantage of an existent method to create safe unique filenames.

*For this test, I didn't created the methods and routes to delete values from the database, making a call from the frontend, as this could be used as room for improvement in a more complete application.*

### Testing
The process of testing the application was quite simple, as it was only a portion of a real-world software.
I have written simple tests for the most common parts, so I could show my ability to work with `PHP Unit` for testing purposes.

### Docker
Docker containers have been implemented using the most simple architecture to do not overload this test with issues regarding infrastructure and to be reliable to test on a quick way.