[![](https://scdn.rapidapi.com/RapidAPI_banner.png)](https://rapidapi.com/package/Wikipedia/functions?utm_source=RapidAPIGitHub_WikipediaFunctions&utm_medium=button&utm_content=RapidAPI_GitHub)

# Wikipedia Package
Wikipedia is a free online encyclopedia with the aim to allow anyone to edit articles. Wikipedia is the largest and most popular general reference work on the Internet,and is ranked the fifth-most popular website. Wikipedia is owned by the nonprofit Wikimedia Foundation.
* Domain: [www.wikipedia.org](https://www.en.wikipedia.org)
* Credentials: username, password

## How to get credentials: 
0. Register on the [www.wikipedia.org](https://en.wikipedia.org)

 ## Custom datatypes: 
  |Datatype|Description|Example
  |--------|-----------|----------
  |Datepicker|String which includes date and time|```2016-05-28 00:00:00```
  |Map|String which includes latitude and longitude coma separated|```50.37, 26.56```
  |List|Simple array|```["123", "sample"]``` 
  |Select|String with predefined values|```sample```
  |Array|Array of objects|```[{"Second name":"123","Age":"12","Photo":"sdf","Draft":"sdfsdf"},{"name":"adi","Second name":"bla","Age":"4","Photo":"asfserwe","Draft":"sdfsdf"}] ```
 
## Wikipedia.getPageByTitles
GetArticleByTitles module allows you to get information about a wiki and the data stored in it, such as the wikitext of a particular page, the links and categories of a set of pages.See more [here](https://www.mediawiki.org/wiki/API:Query).

| Field               | Type  | Description
|---------------------|-------|----------
| titles              | List  | A list of titles to work on.Maximum number of values is 50 .
| additionalProperties| List  | Properties of pages, including page revisions and content.Property queries get various data about a list of pages.
| additionalLists     | List  | Lists of pages that match certain criteria.Lists differ from properties in two aspects - instead of appending data to the elements in the pages element, each list has its own separated branch in the query element.
| export              | Select| Export the current revisions of all given or generated pages.
| indexPageIds        | Select| Include an additional pageids section listing all returned page IDs.
| iwurl               | Select| Whether to get the full URL if the title is an interwiki link.
| customParams        | List  | Additional custom query param.Like this `&key=value`.

## Wikipedia.getPage
GetPage module allows you to get information by id about a wiki and the data stored in it, such as the wikitext of a particular page, the links and categories of a set of pages.See more [here](https://www.mediawiki.org/wiki/API:Query)

| Field               | Type  | Description
|---------------------|-------|----------
| pageIds             | List  | A list of page IDs to work on.Maximum number of values is 50 (500 for bots).
| additionalProperties| List  | Properties of pages, including page revisions and content.Property queries get various data about a list of pages.
| additionalLists     | List  | Lists of pages that match certain criteria.Lists differ from properties in two aspects - instead of appending data to the elements in the pages element, each list has its own separated branch in the query element.
| export              | Select| Export the current revisions of all given or generated pages.
| indexPageIds        | Select| Include an additional pageids section listing all returned page IDs.
| iwurl               | Select| Whether to get the full URL if the title is an interwiki link.
| customParams        | List  | Additional custom query param.Like this `&key=value`.

## Wikipedia.getPageByRevisionId
getPageByRevId module allows you to get information by id about a wiki and the data stored in it, such as the wikitext of a particular page, the links and categories of a set of pages.See more [here](https://www.mediawiki.org/wiki/API:Query)

| Field               | Type  | Description
|---------------------|-------|----------
| revIds              | List  | A list of revision IDs to work on.Maximum number of values is 50 (500 for bots).
| additionalProperties| List  | Properties of pages, including page revisions and content.Property queries get various data about a list of pages.
| additionalLists     | List  | Lists of pages that match certain criteria.Lists differ from properties in two aspects - instead of appending data to the elements in the pages element, each list has its own separated branch in the query element.
| export              | Select| Export the current revisions of all given or generated pages.
| indexPageIds        | Select| Include an additional pageids section listing all returned page IDs.
| iwurl               | Select| Whether to get the full URL if the title is an interwiki link.
| customParams        | List  | Additional custom query param.Like this `&key=value`.

## Wikipedia.getFileUrl
Returns file url.

| Field   | Type  | Description
|---------|-------|----------
| fileName| String| Example - `File:James Hetfield - Cardiff 1996.jpg`.

## Wikipedia.getFilesInfo
Returns file information and upload history.See more [here](https://www.mediawiki.org/wiki/API:Imageinfo);

| Field              | Type      | Description
|--------------------|-----------|----------
| fileNames          | List      | Which file get.
| informationToGet   | List      | Which file information to get.
| informationLimit   | String    | How many total results to return per request.No more than 500 (5000 for bots) allowed. Enter max to use the maximum limit.
| startListingFrom   | DatePicker| Timestamp to start listing from.
| stopListingAt      | DatePicker| Timestamp to stop listing at.
| urlWidth           | String    | If iiprop=url is set, a URL to an image scaled to this width will be returned. For performance reasons if this option is used, no more than 50 scaled images will be returned.
| urlHeight          | String    | Similar to iiurlwidth.
| badFileContextTitle| String    | If badfilecontexttitleprop=badfile is set, this is the page title used when evaluating the MediaWiki:Bad image list.
| customParams       | List      | Additional custom query param.Like this `&key=value`.

## Wikipedia.getWatchList
Get recent changes to pages in the current user's watchlist.See more [here](https://www.mediawiki.org/wiki/API:Watchlist) .

| Field                   | Type      | Description
|-------------------------|-----------|----------
| username                | String    | Your username.
| password                | String    | Your password.
| end                     | DatePicker| The timestamp to end enumerating.
| start                   | DatePicker| The timestamp to start enumerating from.
| includeMultipleRevisions| Select    | Include multiple revisions of the same page within given timeframe.
| wlProp                  | List      | Which additional properties to get.
| wlTypes                 | List      | Which types of changes to show.
| wlLimit                 | Number    | How many total results to return per request.No more than 500 (5000 for bots) allowed. Enter max to use the maximum limit.
| wlToken                 | String    | A security token (available in the user's preferences) to allow access to another user's watchlist.See more [here](https://www.mediawiki.org/wiki/API:Watchlist) .
| wlOwner                 | String    | Used along with wltoken to access a different user's watchlist.
| customParams            | List      | Additional custom query param.Like this `&key=value`.
| wlNamespaces            | String    | Filter changes to only the given namespaces.

## Wikipedia.getPagesCategories
List all categories the pages belong to.

| Field          | Type  | Description
|----------------|-------|----------
| pageIds        | List  | A list of page IDs to work on.Maximum number of values is 50 (500 for bots).
| additionalLists| List  | Lists of pages that match certain criteria.Lists differ from properties in two aspects - instead of appending data to the elements in the pages element, each list has its own separated branch in the query element.
| export         | Select| Export the current revisions of all given or generated pages.
| indexPageIds   | Select| Include an additional pageids section listing all returned page IDs.
| iwUrl          | Select| Whether to get the full URL if the title is an interwiki link.
| rawContinue    | Select| Return raw query-continue data for continuation.
| categoriesLimit| Number| When more results are available, use this to continue.
| clShow         | List  | Which kind of categories to show.
| categoryProp   | List  | Which additional properties to get for each category.
| customParams   | List  | Additional custom query param.Like this `&key=value`.

## Wikipedia.comparePages
Get the difference between two pages.

| Field            | Type  | Description
|------------------|-------|----------
| username         | String| Your username.
| password         | String| Your password.
| fromTitle        | String| First title to compare.
| fromId           | Number| First page ID to compare.
| fromRevisionId   | Number| First revision to compare.
| fromText         | String| Use this text instead of the content of the revision specified by fromtitle, fromid or fromrev.
| fromPst          | Select| Do a pre-save transform on fromtext.
| fromContentFormat| Select| Content serialization format of fromtext.
| fromContentModel | Select| Content model of fromtext. If not supplied, it will be guessed based on the other parameters.
| toTitle          | String| Second title to compare.
| toId             | Number| Second page ID to compare.
| toRevisionId     | Number| Second revision to compare.
| toText           | String| Use this text instead of the content of the revision specified by totitle, toid or torev.
| toRelative       | Select| Use a revision relative to the revision determined from fromtitle, fromid or fromrev. All of the other 'to' options will be ignored.
| toPst            | Select| Do a pre-save transform on totext.
| toContentFormat  | Select| Content serialization format of totext.
| toContentModel   | Select| Content model of totext. If not supplied, it will be guessed based on the other parameters.
| prop             | List  | Which pieces of information to get.
| customParams     | List  | Additional custom query param.Like this `&key=value`.

## Wikipedia.updateMessageList
Edit a mass message delivery list.

| Field   | Type  | Description
|---------|-------|----------
| username| String| Your username.
| password| String| Your password.
| spamList| String| Title of the delivery list to update.
| add     | List  | Titles to add to the list.Maximum number of values is 50 (500 for bots).
| remove  | List  | Titles to remove from the list.Maximum number of values is 50 (500 for bots).

## Wikipedia.sendEmailToUser
You can send email to users who have a confirmed email address.

| Field   | Type  | Description
|---------|-------|----------
| username| String| Your username.
| password| String| Your password.
| target  | String| User to send email to.Example - Rapidapi.
| subject | String| Subject header.
| text    | String| Mail body.
| copyToMe| Select| Send a copy of this mail to me.

## Wikipedia.getRevisionByPageId
Returns revisions for a given page by id, or the latest revision for each of several pages.

| Field           | Type      | Description
|-----------------|-----------|----------
| pageIds         | List      | A list of page IDs to work on.Maximum number of values is 50 (500 for bots).
| revisionProperty| List      | Which properties to get for each revision.
| rvTag           | String    | Only list revisions tagged with this tag.
| rvuser          | String    | Only include revisions made by user.
| rvsection       | String    | Only retrieve the content of this section number.
| rvExcludeUser   | String    | Exclude revisions made by user.
| rvlimit         | Number    | Limit how many revisions will be returned.FNo more than 500 (5000 for bots) allowed. Enter max to use the maximum limit.
| rvEnd           | DatePicker| Enumerate up to this timestamp.
| rvStart         | DatePicker| From which revision timestamp to start enumeration.
| customParams    | List      | Additional custom query param.Like this `&key=value`.

## Wikipedia.getRevisionByPageTitle
Returns revisions for a given page by title, or the latest revision for each of several pages.

| Field           | Type      | Description
|-----------------|-----------|----------
| titles          | List      | A list of titles to work on.Maximum number of values is 50 .
| revisionProperty| List      | Which properties to get for each revision.
| rvTag           | String    | Only list revisions tagged with this tag.
| rvuser          | String    | Only include revisions made by user.
| rvsection       | String    | Only retrieve the content of this section number.
| rvExcludeUser   | String    | Exclude revisions made by user.
| rvlimit         | Number    | Limit how many revisions will be returned.FNo more than 500 (5000 for bots) allowed. Enter max to use the maximum limit.
| rvEnd           | DatePicker| Enumerate up to this timestamp.
| rvStart         | DatePicker| From which revision timestamp to start enumeration.
| customParams    | List      | Additional custom query param.Like this `&key=value`.

## Wikipedia.uploadFile
Uplaod File.

| Field   | Type  | Description
|---------|-------|----------
| username            | String| Your username.
| password            | String| Your password.
| fileUrl | String| URL to fetch the file from.
| fileName| String| Target filename.
| comment | String| Upload comment. Also used as the initial page text for new files if text is not specified.
| text    | String| Initial page text for new files.

## Wikipedia.getPageContent
Get page content by page id.

| Field               | Type  | Description
|---------------------|-------|----------
| pageId              | String| Page id.
| additionalProperties| List  | Properties of pages, including page revisions and content.Property queries get various data about a  page.

## Wikipedia.getFileUsage
Find all pages that use the given files.

| Field   | Type  | Description
|---------|-------|----------
| fileName| String| Target filename.Example - `File:Example.jpg`

## Wikipedia.getAllImageFromPage
Retrieve all image from page by id.

| Field | Type  | Description
|-------|-------|----------
| pageId| String| Page id.Example - 1092923.

## Wikipedia.getCurrentUser
Returns information about the currently logged-in user.

| Field               | Type  | Description
|---------------------|-------|----------
| username            | String| Your username.
| password            | String| Your password.
| additionalProperties| List  | Which pieces of information to include.

