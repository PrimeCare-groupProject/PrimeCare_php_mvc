<?php
class Pagination {
    private $currentPage;
    private $totalPages;
    private $range;

    public function __construct($currentPage, $totalPages, $range = 3) {
        $this->currentPage = max(1, $currentPage);
        $this->totalPages = max(1, $totalPages);
        $this->range = $range; // Number of pages to show before and after current page
    }

    public function generateLinks() {
        $links = [];

        // Ensure the current page is within the valid range
        $page = min($this->currentPage, $this->totalPages);

        // Calculate the start and end of the pagination range
        $startPage = max(1, $page - $this->range);
        $endPage = min($this->totalPages, $page + $this->range);

        // "Previous" Button
        if ($page > 1) {
            $links[] = $this->createLink($page - 1, 'Previous');
        }

        // Add "First" page if not in range
        if ($startPage > 1) {
            $links[] = $this->createLink(1, '1');
            if ($startPage > 2) {
                $links[] = "<span class='pagination-ellipsis'>...</span>"; // Ellipsis for skipped pages
            }
        }

        // Generate Page Number Links within the defined range
        for ($i = $startPage; $i <= $endPage; $i++) {
            $activeClass = ($i == $page) ? 'activebtn' : '';
            $links[] = $this->createLink($i, $i, $activeClass);
        }

        // Add "Last" page if not in range
        if ($endPage < $this->totalPages) {
            if ($endPage < $this->totalPages - 1) {
                $links[] = "<span class='pagination-ellipsis'>...</span>"; // Ellipsis for skipped pages
            }
            $links[] = $this->createLink($this->totalPages, $this->totalPages);
        }

        // "Next" Button
        if ($page < $this->totalPages) {
            $links[] = $this->createLink($page + 1, 'Next');
        }

        return implode("\n", $links);
    }

    private function createLink($page, $label, $class = '') {
        $classAttr = $class ? " class='pagination-button $class'" : " class='pagination-button'";
        $queryParams = $_GET;
        unset($queryParams["url"]);
        $queryParams['page'] = $page;
        $queryString = http_build_query($queryParams);
        return "<a href='?$queryString'$classAttr>$label</a>";
    }
}


// // // // how to use

// // //in the controller
// $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
// $totalPages = 100; // For this example, assume 100 total pages
// // Instantiate the Pagination class with the current page, total pages, and range
// $pagination = new Pagination($currentPage, $totalPages, 3); 
// $paginationLinks = $pagination->generateLinks();    // Generate pagination links
// // Pass pagination links to the view
// $this->view('paginationView', ['paginationLinks' => $paginationLinks]);

//  // //Include the Pagination class in the view
// <div class="pagination">
//         <!-- Render the pagination links -->
//         <?php echo $paginationLinks; ? >
// </div>