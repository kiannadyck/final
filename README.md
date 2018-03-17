# final - Flashcard Website
//Separation using the MVC Pattern
This website separates all database and business logic using the MVC pattern.
Objects/classes are in in their own directory, as are all all other components. The index page is the controller that creates and manages the routes.
HTML pages contain content and are accessed via routes managed by the index page.

//Database and PDO
Database interactions are handled by PHP in the models folder.

All database interactions are handled by function calls, which utilize PDO.

//Fat-Free Framework
The Controller routes all URLs and uses the Fat Free Framework to display information stored in the hive onto HTML pages.

//Data
The user is allowed to access the flashcard tool after they have login credentials, 
created via email and password sign-up. Emails may not be duplicates, and ensures there is one account per email address.
The user may create new flashcards, or update, edit, and delete existing decks.
A user may delete a whole deck or edit, add to, or remove from one. Deck names may also be changed.

//OOP
The decks are questionAnswer Objects. Each time a user creates a new deck, it creates a questionAnswer object, 
which inherits the name property from its super, Deck.

//Docblocks
All php files in the models directory contain DocBlocks that describe the file and individual functions.

//Commits
A bulk of the work was done pair programming. We switched off laptops, to reflect a balance in git commits.

//Validation
PHP checks fields to see if they are empty and will reflect error messages and will not submit information to the database. 
Email addresses are validated by regex on both PHP and Javascript. When one is registering, passwords must match and are validated by
both Javascript and PHP. Email is also validated the same way.

Data fields are sticky when appropriate.

//jQuery and Ajax
jQuery and Ajax are use on the Edit page to change data without refreshing the page.
Each command is controlled by a php controller file that calls the proper function calls from the functions file, thus maintaining
the MVC framework.

