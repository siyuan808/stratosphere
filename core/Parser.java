class Parser {
    static void parse(URL url) {
	if( !isArticle(url) ) {
	    insert url with the original link into the databases;	
	} else {
	    parseUrl(url);
	}
    }

    static boolean isArticle(URL url) {
	for(each tag : url.tags) 
	    if(tag.value.length > threshold)
	    	return true;
	
	return false;
    }

    static void parseUrl(URL url) {
	Get the main content;
	Download all the images under <img> tag, save them into S3;
	Replace all the <img src = "path">, path = the image link on S3;
	Create a new HTML page, save it into S3;
	Insert the path of the HTML into the databases;
    }
}
