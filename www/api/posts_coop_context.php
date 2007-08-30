<?php

// Generate a Google Co-Op context file for the tags

require_once('../header.inc.php');

$tagservice =& ServiceFactory::getServiceInstance('TagService');

// Check to see if a tag was specified.
if (isset($_REQUEST['cse']) && (trim($_REQUEST['cse']) != ''))
    $cse = trim($_REQUEST['cse']);
else
    $cse = "_cse_we9jedjkeci";

// Get the list of tags
$tags = $tagservice->getAllTags();

// Set up the XML file and output all the posts.
header('Content-Type: text/xml');
echo <<<HEADER
<?xml version="1.0" encoding="UTF-8" ?>
<GoogleCustomizations version="1.0">
  <CustomSearchEngine volunteers="true" keywords="oai" 
  Title="OER Search" Description="Test Engine for OER import." language="en">
    <Context refinementsTitle="Refine results for \$q:">
    </Context>
    <LookAndFeel nonprofit="true" />

  </CustomSearchEngine>

HEADER;

/*
foreach ($tags as $tag) {
	# don't output system generated tags
	if ( substr($bTag, 0, 7) != "system:" ) {
		# convert special chars to character entities
		$tag['tag'] = filter($tag['tag'], "xml");
		echo <<<FACET
			<Facet>
				<FacetItem title='{$tag['tag']}'>
					<Label name='{$tag['tag']}' mode='FILTER' />
				</FacetItem>
			</Facet>

FACET;
	}
}

*/

// generate inclusion URLs for the bookmarks
$bookmarks =& $bookmarkservice->getBookmarks(0, NULL, NULL, $tag);
$page_count = (len($bookmarks) // 5000) + 1;

echo "<!-- include the OER Cloud annotations ${page_count} -->\n";

for ($i = 0; $i < $page_count; $i++) {

    echo """
<Include type="Annotations" 
       href="http://oercloud.creativecommons.org/api/posts/coop?p=$i" />
""";
}

echo <<<FOOTER
    <!-- include the OER Cloud annotations ${page_count} -->
    <Include type="Annotations" 
       href="http://oercloud.creativecommons.org/api/posts/coop" />

</GoogleCustomizations>
FOOTER;

?>
