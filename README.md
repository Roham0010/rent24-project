# Project descriptions

## FrontEnd

- I have done some simpel validations to just showcase what can be done.

## Backend:

### The way it's done:

I first load the file into memory and save it to the database to be able to handle it with a sync system and manage to not loose any data during the processing of the data.
I did set the memory limit to maximum because only reading the file once and inserting it to a table in batches will not take much time an resources and it will be okay on production too.

## Notes:

- We have a sync system that for both saving XML data to database and processing the data and inserting actual products it's used, by that sync system everytime we run the sync we will continue where we left off and there will be no data loss and duplications for products.
- The fetchind data from XML file and processing and storing it in database both executes in jobs.
- We have two types of relationships: hasMany for images and hasManyThrough or belongsToMany for categories and product variants.
- The images of each product looked a lot like eachother in the file but I didn't pay attention to create a pivot table for images and just imagined that each product has it's own unique images.
- In the x_m_l_file_chunked_raw_data table I will leave 5 items of chunked data for your reference to see how the data is stored.
- In a real world project we will have something like Horizon for handeling jobs and we will not have any execution time limit exceed problems.
- I did not vendor folder to
