class Retriever {
    ArrayList<Page> getArticleList(User user) {
	ArrayList<PageId> pageIds = query with user.id from "Store" table
	ArrayList<Page> pages = new ArrayList<Page>();
	for( Id pid : pageIds) 
	    pages.append(query with pid from "WebPage" table)
	return pages;
    }

    Page getArticle(PageId pid) {
	Page p = query with pid from "WebPage"
	return p;
    }
}
