<?php
defined('ROOTPATH') or exit('Access denied');

class Owner
{
    use controller;

    private function propertyUnitOwner($propertyId)
    {
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyId])[0];
        //show($propertyUnit);

        $agent = new User;
        $agentDetails = $agent->where(['pid' => $propertyUnit->agent_id])[0];

        $this->view('owner/propertyUnitShowing', ['property' => $propertyUnit, 'agent' => $agentDetails]);
    }

    // public function index()
    // {
    //     $serviceLog = new ServiceLog();
    //     $serviceLogs = $serviceLog->where(['requested_person_id' => $_SESSION['user']->pid]);

    //     $properties = new PropertyConcat;
    //     $properties = $properties->where(['person_id' => $_SESSION['user']->pid], ['status' => 'Inactive']);
    //     $propertyCount = count($properties);

    //     $totalServiceCost = 0;
    //     foreach ($serviceLogs as $log) {
    //         $totalServiceCost += ($log->total_cost);
    //     }

    //     $this->view('owner/dashboard', [
    //         'user' => $_SESSION['user'],
    //         'serviceLogs' => $serviceLogs,
    //         'propertyCount' => $propertyCount,
    //         'totalServiceCost' => $totalServiceCost,
    //     ]);
    // }

    public function index()
    {
        // Models initialization
        $serviceLog = new ServiceLog();
        $property = new PropertyConcat();
        $bookingOrders = new BookingOrders(); // Use BookingOrders instead of BookingModel
        
        $ownerId = $_SESSION['user']->pid;
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        // Get properties owned by the current user
        $properties = $property->where(['person_id' => $ownerId]);
        $propertyCount = is_array($properties) ? count($properties) : 0;
        
        // Get service logs for the owner
        $serviceLogs = $serviceLog->where(['requested_person_id' => $ownerId]);
        
        // Calculate total service costs
        $totalServiceCost = 0;
        if ($serviceLogs) {
            foreach ($serviceLogs as $log) {
                $totalServiceCost += ($log->total_cost ?? 0);
            }
        }
        
        // Get all booking orders for this owner directly 
        // This replaces the old method of looping through properties
        $ownerBookings = $bookingOrders->getOrdersByOwner($ownerId);
        
        // Initialize monthly data structure
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = ($currentMonth - $i) > 0 ? ($currentMonth - $i) : (12 + ($currentMonth - $i));
            $year = ($currentMonth - $i) > 0 ? $currentYear : ($currentYear - 1);
            
            $monthName = date('M', mktime(0, 0, 0, $month, 1, $year));
            $monthlyData[$monthName] = [
                'income' => 0,
                'expense' => 0,
                'profit' => 0
            ];
        }
        
        // Calculate total income and track active bookings from booking orders
        $totalIncome = 0;
        $activeBookings = 0;
        $totalUnits = 0;
        
        if (is_array($ownerBookings) && !empty($ownerBookings)) {
            foreach ($ownerBookings as $booking) {
                // Calculate booking amount based on total_amount field or rental_price * duration
                if (isset($booking->total_amount)) {
                    $bookingAmount = $booking->total_amount;
                } else if (isset($booking->rental_price) && isset($booking->duration)) {
                    $bookingAmount = $booking->rental_price * $booking->duration;
                } else {
                    $bookingAmount = 0;
                }
                
                $totalIncome += $bookingAmount;
                
                // Add to monthly data if start_date is set
                if (isset($booking->start_date)) {
                    $bookingMonth = date('M', strtotime($booking->start_date));
                    if (isset($monthlyData[$bookingMonth])) {
                        $monthlyData[$bookingMonth]['income'] += $bookingAmount;
                    }
                }
                
                // Count active bookings based on booking_status and payment_status
                $bookingStatus = strtolower($booking->booking_status ?? '');
                $paymentStatus = strtolower($booking->payment_status ?? '');
                
                if (($bookingStatus === 'confirmed' || $bookingStatus === 'active') && 
                    $paymentStatus === 'paid') {
                    $activeBookings++;
                }
            }
        }
        
        // Calculate property units for occupancy rate
        if ($properties) {
            foreach ($properties as $prop) {
                $totalUnits += ($prop->units ?? 1);
            }
        }
        
        // Fill in monthly service log data (expenses)
        if ($serviceLogs) {
            foreach ($serviceLogs as $log) {
                if (isset($log->date)) {
                    $logMonth = date('M', strtotime($log->date));
                    if (isset($monthlyData[$logMonth])) {
                        $monthlyData[$logMonth]['expense'] += ($log->total_cost ?? 0);
                    }
                }
            }
        }
        
        // Calculate profit for each month and overall
        $profit = $totalIncome - $totalServiceCost;
        foreach ($monthlyData as $month => &$data) {
            $data['profit'] = $data['income'] - $data['expense'];
        }
        
        // Calculate booking/occupancy rate
        $occupancyRate = ($totalUnits > 0) ? min(100, ($activeBookings / $totalUnits) * 100) : 0;
        
        // Get expense types for pie chart
        $expenseTypes = [];
        if ($serviceLogs) {
            foreach ($serviceLogs as $log) {
                $type = $log->service_type ?? 'Other';
                if (!isset($expenseTypes[$type])) {
                    $expenseTypes[$type] = 0;
                }
                $expenseTypes[$type] += ($log->total_cost ?? 0);
            }
        }
        
        // Get recent transactions (combine service logs and bookings)
        $recentTransactions = [];
        
        // Add service logs as expenses
        if ($serviceLogs) {
            foreach ($serviceLogs as $log) {
                $recentTransactions[] = [
                    'date' => $log->date ?? '',
                    'description' => $log->service_description ?? $log->service_type ?? 'Service',
                    'category' => 'Expense',
                    'amount' => -($log->total_cost ?? 0),
                    'status' => $log->status ?? 'Pending'
                ];
            }
        }
        
        // Add booking orders as income
        if (is_array($ownerBookings) && !empty($ownerBookings)) {
            foreach ($ownerBookings as $booking) {
                // Calculate amount using total_amount or rental_price * duration
                if (isset($booking->total_amount)) {
                    $amount = $booking->total_amount;
                } else if (isset($booking->rental_price) && isset($booking->duration)) {
                    $amount = $booking->rental_price * $booking->duration;
                } else {
                    $amount = 0;
                }
                
                $recentTransactions[] = [
                    'date' => $booking->start_date ?? '',
                    'description' => 'Booking for Property ID: ' . ($booking->property_id ?? 'Unknown'),
                    'category' => 'Income',
                    'amount' => $amount,
                    'status' => $booking->booking_status ?? 'Pending'
                ];
            }
        }
        
        // Sort transactions by date (newest first)
        usort($recentTransactions, function($a, $b) {
            return strtotime($b['date'] ?? 0) - strtotime($a['date'] ?? 0);
        });
        
        // Take just the 10 most recent transactions
        $recentTransactions = array_slice($recentTransactions, 0, 10);
    
        $this->view('owner/dashboard', [
            'user' => $_SESSION['user'],
            'properties' => $properties,
            'propertyCount' => $propertyCount,
            'serviceLogs' => $serviceLogs,
            'totalServiceCost' => $totalServiceCost,
            'totalIncome' => $totalIncome,
            'profit' => $profit,
            'activeBookings' => $activeBookings,
            'totalUnits' => $totalUnits,
            'bookingRate' => $occupancyRate,
            'occupancyRate' => $occupancyRate,
            'monthlyData' => $monthlyData,
            'expenseTypes' => $expenseTypes,
            'recentTransactions' => $recentTransactions,
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    public function dashboard()
    {
        // Models initialization
        $serviceLog = new ServiceLog();
        $booking = new BookingModel();
        $property = new Property();
        
        $ownerId = $_SESSION['user']->pid;
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        // Get properties owned by the current user
        $properties = $property->where(['person_id' => $ownerId]);
        $propertyCount = count($properties ?? []);
        
        // Extract property IDs
        $propertyIds = [];
        if ($properties) {
            foreach ($properties as $p) {
                $propertyIds[] = $p->property_id;
            }
        }
        
        // Get service logs for the owner
        $serviceLogs = $serviceLog->where(['requested_person_id' => $ownerId]);
        
        // Calculate total service costs
        $totalServiceCost = 0;
        if ($serviceLogs) {
            foreach ($serviceLogs as $log) {
                $totalServiceCost += ($log->total_cost ?? 0);
            }
        }
        
        // Get bookings for owner's properties
        $allBookings = [];
        foreach ($propertyIds as $propId) {
            $propertyBookings = $booking->where(['property_id' => $propId]);
            if ($propertyBookings) {
                $allBookings = array_merge($allBookings, $propertyBookings);
            }
        }
        
        // Calculate total income from bookings
        $totalIncome = 0;
        $activeBookings = 0;
        if ($allBookings) {
            foreach ($allBookings as $booking) {
                $totalIncome += ($booking->price ?? 0) * ($booking->renting_period ?? 0);
                
                // Count active bookings
                if (isset($booking->status) && strtolower($booking->status) === 'active') {
                    $activeBookings++;
                } else if (isset($booking->accept_status) && strtolower($booking->accept_status) === 'accepted') {
                    $activeBookings++;
                }
            }
        }
        
        // Calculate profit
        $profit = $totalIncome - $totalServiceCost;
        
        // Prepare monthly data for charts - Last 6 months
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = ($currentMonth - $i) > 0 ? ($currentMonth - $i) : (12 + ($currentMonth - $i));
            $year = ($currentMonth - $i) > 0 ? $currentYear : ($currentYear - 1);
            
            $monthName = date('M', mktime(0, 0, 0, $month, 1, $year));
            $monthlyData[$monthName] = [
                'income' => 0,
                'expense' => 0,
                'profit' => 0,
            ];
        }
        
        // Fill in monthly booking data
        if ($allBookings) {
            foreach ($allBookings as $booking) {
                if (isset($booking->start_date)) {
                    $bookingMonth = date('M', strtotime($booking->start_date));
                    if (isset($monthlyData[$bookingMonth])) {
                        $monthlyData[$bookingMonth]['income'] += ($booking->price ?? 0);
                    }
                }
            }
        }
        
        // Fill in monthly service log data
        if ($serviceLogs) {
            foreach ($serviceLogs as $log) {
                if (isset($log->date)) {
                    $logMonth = date('M', strtotime($log->date));
                    if (isset($monthlyData[$logMonth])) {
                        $monthlyData[$logMonth]['expense'] += ($log->total_cost ?? 0);
                    }
                }
            }
        }
        
        // Calculate monthly profit
        foreach ($monthlyData as $month => &$data) {
            $data['profit'] = $data['income'] - $data['expense'];
        }
        
        // Get service types for pie chart
        $expenseTypes = [];
        if ($serviceLogs) {
            foreach ($serviceLogs as $log) {
                $type = $log->service_type ?? 'Other';
                if (!isset($expenseTypes[$type])) {
                    $expenseTypes[$type] = 0;
                }
                $expenseTypes[$type] += ($log->total_cost ?? 0);
            }
        }
        
        // Get recent transactions (combine service logs and bookings)
        $recentTransactions = [];
        
        // Add service logs as expenses
        if ($serviceLogs) {
            foreach ($serviceLogs as $log) {
                $recentTransactions[] = [
                    'date' => $log->date ?? '',
                    'description' => $log->service_description ?? $log->service_type ?? 'Service',
                    'category' => 'Expense',
                    'amount' => -($log->total_cost ?? 0),
                    'status' => $log->status ?? 'Pending'
                ];
            }
        }
        
        // Add bookings as income
        if ($allBookings) {
            foreach ($allBookings as $booking) {
                $recentTransactions[] = [
                    'date' => $booking->start_date ?? $booking->booked_date ?? '',
                    'description' => 'Booking for Property ID: ' . ($booking->property_id ?? 'Unknown'),
                    'category' => 'Income',
                    'amount' => ($booking->price ?? 0),
                    'status' => $booking->status ?? $booking->accept_status ?? 'Pending'
                ];
            }
        }
        
        // Sort transactions by date (newest first)
        usort($recentTransactions, function($a, $b) {
            return strtotime($b['date'] ?? 0) - strtotime($a['date'] ?? 0);
        });
        
        // Take just the 10 most recent transactions
        $recentTransactions = array_slice($recentTransactions, 0, 10);
        
        $this->view('owner/dashboard', [
            'user' => $_SESSION['user'],
            'properties' => $properties,
            'propertyCount' => $propertyCount,
            'serviceLogs' => $serviceLogs,
            'totalServiceCost' => $totalServiceCost,
            'totalIncome' => $totalIncome,
            'profit' => $profit,
            'activeBookings' => $activeBookings,
            'monthlyData' => $monthlyData,
            'expenseTypes' => $expenseTypes,
            'recentTransactions' => $recentTransactions,
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    public function maintenance()
    {
        // Get the current user's ID
        $ownerId = $_SESSION['user']->pid;

        // Instantiate the ServiceLog model
        $serviceLog = new ServiceLog();

        
        // Get service logs directly based on requested_person_id
        $serviceLogs = $serviceLog->where(['requested_person_id' => $ownerId]);
        
        // If no service logs found, initialize as empty array
        if (!$serviceLogs) {
            $serviceLogs = [];
        }
    
        // Apply status filtering
        if (!empty($_GET['status_filter'])) {
            $status = $_GET['status_filter'];
            $filteredLogs = [];
            foreach ($serviceLogs as $log) {
                if (strtolower($log->status) == strtolower($status)) {
                    $filteredLogs[] = $log;
                }
            }
            $serviceLogs = $filteredLogs;
        }
    
        // Apply date range filtering
        if (!empty($_GET['date_from']) || !empty($_GET['date_to'])) {
            $dateFrom = !empty($_GET['date_from']) ? strtotime($_GET['date_from']) : null;
            $dateTo = !empty($_GET['date_to']) ? strtotime($_GET['date_to'] . ' 23:59:59') : null;

            $filteredLogs = [];
            foreach ($serviceLogs as $log) {
                $logDate = strtotime($log->date);

                // Check if the log date is within the specified range
                $includeLog = true;
                if ($dateFrom && $logDate < $dateFrom) $includeLog = false;
                if ($dateTo && $logDate > $dateTo) $includeLog = false;

                if ($includeLog) {
                    $filteredLogs[] = $log;
                }
            }
            $serviceLogs = $filteredLogs;
        }

        // Apply sorting
        if (!empty($_GET['sort'])) {
            $sort = $_GET['sort'];

            usort($serviceLogs, function ($a, $b) use ($sort) {
                switch ($sort) {
                    case 'date_asc':
                        return strtotime($a->date) - strtotime($b->date);
                    case 'date_desc':
                        return strtotime($b->date) - strtotime($a->date);
                    case 'property_id':
                        if (isset($a->property_id) && isset($b->property_id)) {
                            return $a->property_id <=> $b->property_id;
                        }
                        return 0;
                    default:
                        return strtotime($b->date) - strtotime($a->date); // Default: newest first
                }
            });
        }

        // Calculate total expenses
        $totalExpenses = 0;
        foreach ($serviceLogs as $log) {
            $totalExpenses += ($log->total_cost ?? 0);
        }

        $this->view('owner/maintenance', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'serviceLogs' => $serviceLogs,
            'totalExpenses' => $totalExpenses
        ]);
    }

    public function financeReport()
    {
        // Get the current user's ID
        $ownerId = $_SESSION['user']->pid ?? 0;
    
        // Get user details from the User model
        $userModel = new User();
        $userData = $userModel->where(['pid' => $ownerId])[0] ?? null;
    
        // Initialize models
        $property = new PropertyConcat;
        $booking = new BookingModel();
        $serviceLog = new ServiceLog();
    
        // Get all properties owned by current user
        $properties = $property->where(['person_id' => $ownerId]);
        
        // Ensure properties is always an array, even if the query returns false
        if (!is_array($properties)) {
            $properties = [];
        }
        
        $propertyIds = [];
    
        if (!empty($properties)) {
            foreach ($properties as $prop) {
                $propertyIds[] = $prop->property_id;
            }
        }
    
        // Initialize data
        $bookings = [];
        $serviceLogs = [];
        $totalIncome = 0;
        $totalExpenses = 0;
        $activeBookings = 0;
        $totalUnits = 0;
    
        // Calculate monthly data for the last 6 months
        $currentMonth = date('n');
        $currentYear = date('Y');
    
        // Initialize monthly data structure
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = ($currentMonth - $i) > 0 ? ($currentMonth - $i) : (12 + ($currentMonth - $i));
            $year = ($currentMonth - $i) > 0 ? $currentYear : ($currentYear - 1);
    
            $monthName = date('M', mktime(0, 0, 0, $month, 1, $year));
            $monthlyData[$monthName] = [
                'income' => 0,
                'expense' => 0,
                'profit' => 0,
                'occupancy_rate' => 0
            ];
        }
    
        // Get data only for properties owned by the current user
        if (!empty($propertyIds)) {
            // Get all bookings for owner's properties
            foreach ($propertyIds as $propId) {
                $propertyBookings = $booking->where(['property_id' => $propId]);
                
                // Ensure propertyBookings is always an array
                if (!is_array($propertyBookings) && $propertyBookings !== null) {
                    $propertyBookings = [];
                }
                
                if (!empty($propertyBookings)) {
                    foreach ($propertyBookings as $b) {
                        $bookings[] = $b;
    
                        // Calculate income
                        $totalIncome += $b->price;
    
                        // Track active bookings - UPDATED LOGIC
                        if (isset($b->status) && strtolower($b->status) === 'active') {
                            $activeBookings++;
                        } else if (isset($b->accept_status) && strtolower($b->accept_status) === 'accepted') {
                            // Also count bookings with 'accepted' status if they don't have an explicit 'active' status
                            $activeBookings++;
                        }
    
                        // Add to monthly data
                        if (isset($b->start_date)) {
                            $bookingMonth = date('M', strtotime($b->start_date));
                            if (isset($monthlyData[$bookingMonth])) {
                                $monthlyData[$bookingMonth]['income'] += $b->price;
                            }
                        }
                    }
                }         
                // Get service logs for properties by requested person ID
                $userServiceLogs = $serviceLog->where([
                    'requested_person_id' => $ownerId,
                    'property_id' => $propId
                ]);
                
                // Ensure userServiceLogs is always an array
                if (!is_array($userServiceLogs) && $userServiceLogs !== null) {
                    $userServiceLogs = [];
                }
                
                if(!empty($userServiceLogs)) {
                    foreach($userServiceLogs as $log) {
                        $serviceLogs[] = $log;
    
                        // Calculate expenses
                        $cost = $log->total_cost;
                        $totalExpenses += $cost;
    
                        // Add to monthly data
                        if (isset($log->date)) {
                            $logMonth = date('M', strtotime($log->date));
                            if (isset($monthlyData[$logMonth])) {
                                $monthlyData[$logMonth]['expense'] += $cost;
                            }
                        }
                    }
                }
    
                // Track total units for occupancy calculation
                $prop = $property->where(['property_id' => $propId])[0] ?? null;
                if ($prop && isset($prop->units)) {
                    $totalUnits += $prop->units;
                }
            }
        }
    
        // UFetch tenant details using customer_id from the booking table
        $tenantDetails = [];
        if (!empty($bookings)) {
            // Collect all unique customer_ids from bookings
            $customerIds = [];
            foreach ($bookings as $booking) {
                if (isset($booking->customer_id) && !in_array($booking->customer_id, $customerIds)) {
                    $customerIds[] = $booking->customer_id;
                }
            }
    
            // Get tenant details using findByMultiplePids method
            if (!empty($customerIds)) {
                $tenants = $userModel->findByMultiplePids($customerIds);
                
                // Ensure tenants is always an array
                if (!is_array($tenants) && $tenants !== null) {
                    $tenants = [];
                }
    
                if ($tenants) {
                    foreach ($tenants as $tenant) {
                        // Create an association by pid for easier lookup
                        $tenantDetails[$tenant->pid] = $tenant;
                    }
                }
            }
        }
    
        // Calculate profit and occupancy rate
        $profit = $totalIncome - $totalExpenses;
        $occupancyRate = ($totalUnits > 0) ? (($activeBookings / $totalUnits) * 100) : 0;
    
        foreach ($monthlyData as $month => &$data) {
            $data['profit'] = $data['income'] - $data['expense'];
            $data['occupancy_rate'] = $occupancyRate;
        }
    
        // Prepare data for the view
        $viewData = [
            'user' => $userData,
            'tenantDetails' => $tenantDetails,
            'status' => $_SESSION['status'] ?? '',
            'properties' => $properties,
            'bookings' => $bookings,
            'serviceLogs' => $serviceLogs,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'profit' => $profit,
            'occupancyRate' => $occupancyRate,
            'monthlyData' => $monthlyData,
            'activeBookings' => $activeBookings,
            'totalUnits' => $totalUnits
        ];
    
        $this->view('owner/financeReport', $viewData);
    }

    public function tenants()
    {
        $user = new User();
        $userData = $user->where(['pid' => $_SESSION['user']->pid])[0];

        $property = new PropertyConcat;
        $properties = $property->where(['person_id' => $_SESSION['user']->pid], ['status' => 'Inactive']);

        //$booking = new BookingModel();
        //$bookings = $booking->where(['person_id' => $_SESSION['user']->pid], ['status' => 'Active']);
        $bookings = [];
        $tenantDetails = [];


        $viewData = [
            'user' => $userData,
            'tenantDetails' => $tenantDetails,
            'properties' => $properties,
            'bookings' => $bookings
        ];

        $this->view('owner/tenants', $viewData);
    }

    private function addProperty()
    {
        $this->view('owner/addProperty', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    private function deletePropertyImage($propertyId)
    {
        $propertyImage = new PropertyImageModel;
        // Fetch all images associated with the property
        $images = $propertyImage->where(['property_id' => $propertyId]);
        show($images);
        foreach ($images as $image) {
            $imagePath = ROOTPATH . "public/assets/images/uploads/property_images/" . $image->image_url;
            show($imagePath);
            // Check if the file exists before attempting to delete
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }

    // delete
    private function deletePropertyDocument($propertyId)
    {
        $propertyDoc = new PropertyDocModel;
        // Fetch all documents associated with the property
        $docs = $propertyDoc->where(['property_id' => $propertyId])[0];
        $docPath = ROOTPATH . "public/assets/documents/property_docs/" . $docs->image_url;
        show($docPath);
        if (file_exists($docPath)) {
            unlink($docPath);
        }
    }

    public function delete($propertyId)
    {
        $property = new PropertyModel;

        // Delete the property images
        $this->deletePropertyImage($propertyId);

        // Delete the property documents
        $this->deletePropertyDocument($propertyId);

        // Delete the property
        $property->delete($propertyId, 'property_id');

        $_SESSION['status'] = 'Property deleted successfully!';

        // Redirect to the property listing page
        redirect('dashboard/propertylisting');
    }

    private function updateProperty($propertyId)
    {
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyId])[0];
        //show($propertyUnit);
        $this->view('owner/editPropertyEnh', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'property' => $propertyUnit
        ]);
    }

    public function propertyListing($a = '', $b = '', $c = '', $d = '')
    {
        if ($a == 'addproperty') {
            $this->addProperty($b = '', $c = '', $d = '');
            return;
        } else if ($a == "repairlisting") {
            $this->repairListing($b = '', $c = '', $d = '');
            return;
        } else if ($a == "servicerequest") {
            $this->serviceRequest($b = '', $c = '', $d = '');
            return;
        } else if ($a == "updateproperty") {
            $this->updateProperty($propertyId = $b);
            return;
        } else if ($a == "propertyunitowner") {
            $this->propertyUnitOwner($propertyId = $b);
            return;
        } else if ($a == "financialreportunit") {
            $this->financialReportUnit($propertyId = $b);
            return;
        } else if ($a == "reportproblem") {
            $this->reportProblem($propertyId = $b);
            return;
        } else if ($a == "dropProperty") {
            $this->dropProperty($propertyId = $b);
            return;
        } else if ($a == "create") {
            $this->create($propertyId = $b);
            return;
        } else if ($a == "update") {
            $this->update($propertyId = $b);
            return;
        } else if ($a == "deleteView") {
            $this->deleteView($propertyId = $b);
            return;
        } else if ($a == "deleteRequest") {
            $this->deleteRequest($propertyId = $b);
            return;
        } else if ($a == "review") {
            $this->reviewUnit($propertyId = $b);
            return;
        } else if ($a == "addImages") {
            $this->addImages($propertyId = $b);
            return;
        } else if ($a == "changeImages") {
            $this->changeImages($propertyId = $b);
            return;
        }

        //if property listing is being called
        $property = new PropertyConcat;
        $properties = $property->where(['person_id' => $_SESSION['user']->pid], ['status' => 'Inactive']);

        $this->view('owner/propertyListing', ['properties' => $properties]);
    }

    public function changeImages($propertyId)
    {
        $removed_images = $_POST['remove_images'] ?? [];
        $removed_images_count = count($removed_images);
        $new_images = $_FILES['property_images'] ?? null;
        $new_images_names = array_filter($new_images['name']); // only names
        $new_images_count = count($new_images_names);

        if ($removed_images || $new_images) {
            $propertyImage = new PropertyImageModel;
            $currentImageCount = count($propertyImage->where(['property_id' => $propertyId]));
            if ($currentImageCount - $removed_images_count + $new_images_count < 1) {
                $_SESSION['flash']['msg'] = 'You must have at least one image.';
                $_SESSION['flash']['type'] = 'warning';
                redirect('dashboard/propertyListing/addImages/' . $propertyId);
                return;
            }

            if (!empty($removed_images)) {
                foreach ($removed_images as $image) {
                    $propertyImage->delete($image, 'image_url');
                    $imagePath = ROOTPATH . "public/assets/images/uploads/property_images/" . $image;
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }
    
            if ($new_images_count) {
                $imageErrors = upload_image(
                    $new_images,
                    ROOTPATH . 'public/assets/images/uploads/property_images/',
                    new PropertyImageModel(),
                    $propertyId,
                    [
                        'allowed_ext' => ['jpg', 'jpeg', 'png'],
                        'prefix' => 'property',
                        'url_field' => 'image_url',
                        'fk_field' => 'property_id'
                    ]
                );
                if (!empty($imageErrors)) {
                    $_SESSION['flash']['msg'] = 'Failed to upload new images.';
                    $_SESSION['flash']['type'] = 'error';
                } else {
                    $_SESSION['flash']['msg'] = 'Images updated successfully.';
                    $_SESSION['flash']['type'] = 'success';
                }
            } else{
                $_SESSION['flash']['msg'] = 'No new images selected for upload.';
                $_SESSION['flash']['type'] = 'warning';
            }

            redirect('dashboard/propertyListing/propertyunitowner/' . $propertyId);
        } else {
            $_SESSION['flash']['msg'] = 'No images selected for upload or removal.';
            $_SESSION['flash']['type'] = 'warning';
            redirect('dashboard/propertyListing/addImages/' . $propertyId);
        }
    }

    public function addImages($propertyId)
    {
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyId])[0];

        $this->view('owner/addImages', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'property' => $propertyUnit
        ]);
    }

    public function reviewUnit($propertyId)
    {
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyId])[0];

        $this->view('owner/singleReview', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'property' => $propertyUnit
        ]);
    }

    public function deleteView($propertyId)
    {
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyId])[0];

        $this->view('owner/deleteProperty', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'property' => $propertyUnit
        ]);
    }

    private function repairListing()
    {
        // Instantiate the Services model and fetch all services
        $servicesModel = new Services();
        $services = $servicesModel->getAllServices();

        // Check and fix image paths for each service
        if (!empty($services)) {
            foreach ($services as $key => $service) {
                // If service_img is empty, skip
                if (empty($service->service_img)) {
                    continue;
                }

                // Check if the image exists in the specified location
                $imagePath = ROOTPATH . 'public/assets/images/repairimages/' . $service->service_img;
                if (!file_exists($imagePath)) {
                    // Try to find the image with common image extensions
                    $found = false;
                    $extensions = ['jpg', 'jpeg', 'png', 'gif'];
                    $baseName = pathinfo($service->service_img, PATHINFO_FILENAME);

                    foreach ($extensions as $ext) {
                        $testPath = ROOTPATH . 'public/assets/images/repairimages/' . $baseName . '.' . $ext;
                        if (file_exists($testPath)) {
                            $services[$key]->service_img = $baseName . '.' . $ext;
                            $found = true;
                            break;
                        }
                    }

                    // If still not found, set to empty to use placeholder
                    if (!$found) {
                        $services[$key]->service_img = '';
                    }
                }
            }
        }

        $this->view('owner/repairListing', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'services' => $services  // Pass the services data to the view
        ]);
    }

    public function serviceRequest($type = '')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle form submission here
            $data = [
                'service_type' => $_POST['service_type'],
                'date' => $_POST['date'],
                'property_id' => $_POST['property_id'],
                'property_name' => $_POST['property_name'],
                'status' => $_POST['status'],
                'service_description' => $_POST['service_description'],
                'cost_per_hour' => $_POST['cost_per_hour'],
                'requested_person_id' => $_POST['requested_person_id'] ?? $_SESSION['user']->pid
            ];

            // Add service request to database
            $service = new ServiceLog();
            if ($service->insert($data)) {
                $_SESSION['status'] = "Service request submitted successfully";
                $_SESSION['success_message'] = "Request sent successfully!";
            } else {
                $_SESSION['errors'][] = "Failed to submit service request";
            }

            redirect('dashboard/serviceRequest');
            return;
        }

        // Get property information from URL parameters
        $property_id = $_GET['property_id'] ?? null;
        $property_name = $_GET['property_name'] ?? '';
        $service_type = $_GET['type'] ?? $type;
        $cost_per_hour = $_GET['cost_per_hour'] ?? '';

        // Fetch property image if property_id is provided
        $propertyImage = null;
        if ($property_id) {
            $imageModel = new PropertyImageModel();
            $images = $imageModel->where(['property_id' => $property_id]);
            if (!empty($images)) {
                $propertyImage = $images[0]->image_url;
            }
        }

        $this->view('owner/serviceRequest', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'success_message' => $_SESSION['success_message'] ?? '',
            'type' => $service_type,
            'property_id' => $property_id,
            'property_name' => $property_name,
            'property_image' => $propertyImage,
            'cost_per_hour' => $cost_per_hour,
        ]);

        // Clear session messages after displaying
        unset($_SESSION['success_message']);
        unset($_SESSION['errors']);
        unset($_SESSION['status']);
    }

    public function profile()
    {
        $user = new User();

        // notifications
        if ($_SESSION['user']->AccountStatus == 3) { // reject update
            // update data
            $updateAcc = $user->update($_SESSION['user']->pid, [
                'AccountStatus' => 1
            ], 'pid');
            // update session
            if ($updateAcc) {
                $_SESSION['user']->AccountStatus = 1;
            }
            // set message
            $_SESSION['flash']['msg'] = "Your account update has been rejected.";
            $_SESSION['flash']['type'] = "error";
        } elseif ($_SESSION['user']->AccountStatus == 4) { // Approved update
            // update data
            $updateAcc = $user->update($_SESSION['user']->pid, [
                'AccountStatus' => 1
            ], 'pid');
            // update session
            if ($updateAcc) {
                $_SESSION['user']->AccountStatus = 1;
            }
            $_SESSION['flash']['msg'] = "Your account has been accepted.";
            $_SESSION['flash']['type'] = "success";
        } elseif ($_SESSION['user']->AccountStatus == -3) { // Reject delete
            // update data
            $updateAcc = $user->update($_SESSION['user']->pid, [
                'AccountStatus' => 1
            ], 'pid');
            // update session
            if ($updateAcc) {
                $_SESSION['user']->AccountStatus = 1;
            }
            $_SESSION['flash']['msg'] = "Account removal was Rejected.";
            $_SESSION['flash']['type'] = "error";
        } elseif ($_SESSION['user']->AccountStatus == -4) { // Approve delete
            // update data
            $updateAcc = $user->update($_SESSION['user']->pid, [
                'AccountStatus' => 1
            ], 'pid');
            // update session
            if ($updateAcc) {
                $_SESSION['user']->AccountStatus = 1;
            }
            $_SESSION['flash']['msg'] = "Account removal was Rejected.";
            $_SESSION['flash']['type'] = "error";
        } elseif ($_SESSION['user']->AccountStatus == 2) { // update pending

            $_SESSION['flash']['msg'] = "Account update request is pending.";
            $_SESSION['flash']['type'] = "warning";
        } elseif ($_SESSION['user']->AccountStatus == -2) { // Delete pending

            $_SESSION['flash']['msg'] = "Account removal request is pending.";
            $_SESSION['flash']['type'] = "warning";
        } // 0 for deleted in home page

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['delete_account'])) {
                $errors = [];
                $status = '';
                //AccountStatus
                $userId = $_SESSION['user']->pid; // Replace with actual user ID from session

                // Update the user's Account Status to 0 instead od deleting accounnt
                $updated = $user->update($userId, [
                    'AccountStatus' => -2 //pending deletion
                ], 'pid');

                if ($updated) {
                    $_SESSION['user']->AccountStatus = -2; // Assuming 0 indicates a deleted account

                    $_SESSION['flash']['msg'] = "Deletion Request sent.Please wait for appoval.";
                    $_SESSION['flash']['type'] = "success";
                } else {
                    $_SESSION['flash']['msg'] = "Failed to request deletion of account. Please try again.";
                    $_SESSION['flash']['type'] = "error";
                    // $errors[] = "Failed to delete account. Please try again.";
                }

                // Store errors in session and redirect back
                $_SESSION['errors'] = $errors;
                redirect('dashboard/profile');
            } else if (isset($_POST['logout'])) {
                $this->logout();
            }
            $this->handleProfileSubmission();
            // return;
        }
        $this->view('profile', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);

        // Clear session data after rendering the view
        unset($_SESSION['errors']);
        unset($_SESSION['status']);
        return;
    }

    private function handleProfileSubmission()
    {
        $errors = [];
        $status = '';
        $targetFile = null;

        // Get form data and sanitize inputs
        $firstName = esc($_POST['fname'] ?? null);
        $lastName = esc($_POST['lname'] ?? null);
        $contactNumber = esc($_POST['contact'] ?? null);

        $email = filter_var($_POST['email'] ?? null, FILTER_VALIDATE_EMAIL);
        // Validate email
        if (!$email) {
            $errors[] = "Invalid email format.";
        } else {
            // Optional: Check against allowed domains
            $domain = substr($email, strpos($email, '@') + 1);

            $allowedDomains = [
                // Professional Email Providers
                'gmail.com',
                'outlook.com',
                'yahoo.com',
                'hotmail.com',
                'protonmail.com',
                'icloud.com',

                // Tech Companies
                'google.com',
                'microsoft.com',
                'apple.com',
                'amazon.com',
                'facebook.com',
                'twitter.com',
                'linkedin.com',

                // Common Workplace Domains
                'company.com',
                'corp.com',
                'business.com',
                'enterprise.com',

                // Educational Institutions
                'university.edu',
                'college.edu',
                'school.edu',
                'campus.edu',

                // Government and Public Sector
                'gov.com',
                'public.org',
                'municipality.gov',

                // Startup and Tech Ecosystem
                'startup.com',
                'techcompany.com',
                'innovate.com',

                // Freelance and Remote Work
                'freelancer.com',
                'consultant.com',
                'remote.work',

                // Regional and Local Businesses
                'localbank.com',
                'regional.org',
                'cityservice.com',

                // Healthcare and Medical
                'hospital.org',
                'clinic.com',
                'medical.net',

                // Non-Profit and NGO
                'nonprofit.org',
                'charity.org',
                'ngo.com',

                // Creative Industries
                'design.com',
                'creative.org',
                'agency.com',

                // Personal Domains
                'me.com',
                'personal.com',
                'home.net',

                // International Email Providers
                'mail.ru',
                'yandex.com',
                'gmx.com',
                'web.de'
            ];

            if (!in_array($domain, $allowedDomains)) {
                $errors[] = 'Email domain is not allowed';
            } else {
                $user = new User();
                $availableUser = $user->first(['email' => $email]);
                if ($availableUser && $availableUser->pid != $_SESSION['user']->pid) {
                    $errors[] = "Email already exists. Use another one.";
                }
            }
        }

        if (!empty($errors)) {
            $errorString = implode("<br>", $errors);
            $_SESSION['flash']['msg'] = $errorString;
            $_SESSION['flash']['type'] = "error";
            // $_SESSION['errors'] = $errors;
            $_SESSION['status'] = $status;
            redirect('dashboard/profile');
            exit;
        }
        $user = new UserChangeDetails();

        if (!$user->validate($_POST)) {
            $validationErrors = [];
            foreach ($user->errors as $error) {
                $validationErrors[] = $error;
            }
            $errorString = implode("<br>", $validationErrors);
            $_SESSION['flash']['msg'] = $errorString;
            $_SESSION['flash']['type'] = "error";
            $_SESSION['status'] = $status;

            redirect('dashboard/profile');
            exit;
        }
        // Check if profile picture is uploaded
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $profilePicture = $_FILES['profile_picture'];

            // if profilepicsize is larger than 4 mb set error
            if ($profilePicture['size'] > 2 *  1024 * 1024) {
                $errors[] = "Profile picture size must be less than 2MB.";
            }

            // Define the target directory and create it if it doesn't exist
            $targetDir = ".." . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "profile_pictures" . DIRECTORY_SEPARATOR;
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            // Define the target file name using uniqid()
            $imageFileType = strtolower(pathinfo($profilePicture['name'], PATHINFO_EXTENSION));
            $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            // Check if the file is an image
            $validMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($profilePicture['type'], $validMimeTypes)) {
                $errors[] = "Invalid file type. Please upload an image file.";
            }

            if (in_array($imageFileType, $validExtensions)) {
                $targetFile = uniqid() . "__" .  $email . '.' . $imageFileType;

                // Move the uploaded file
                if (move_uploaded_file($profilePicture['tmp_name'], $targetDir . $targetFile)) {
                    $status = "Profile picture uploaded successfully!" . $targetFile;
                } else {
                    $errors[] = "Error uploading profile picture. Try changing the file name." . $profilePicture['tmp_name'] . $targetFile;
                }
            } else {
                $errors[] = "Invalid file type. Please upload an image file.";
            }
        }

        // Update user profile in the database
        if (empty($errors) && $_SESSION['user']->pid) {
            if (!isset($_SESSION['user']) || !property_exists($_SESSION['user'], 'pid')) {
                $errors[] = "User session is not valid.";
            }
            $userId = $_SESSION['user']->pid;
            $user = new UserChangeDetails();
            // show($user->findAll());

            // Check if a record already exists for the user
            $isUserEdited = $user->first(['pid' => $userId]);
            // var_dump($isUserEdited);
            // Dynamically build the data array with only available fields
            $data = [];
            $data['pid'] = $userId;

            if (!empty($firstName)) $data['fname'] = $firstName;
            if (!empty($lastName)) $data['lname'] = $lastName;
            if (!empty($email)) $data['email'] = $email;
            if (!empty($contactNumber)) $data['contact'] = $contactNumber;
            if (!empty($targetFile)) $data['image_url'] = $targetFile;
            // die();
            // If no data is provided, skip the operation
            if (empty($data)) {
                $errors[] = "No data provided to update.<br>";
            } else {
                if (!$isUserEdited) {
                    // Include the pid when inserting a new record
                    $updated = $user->insert($data);
                } else {
                    // Update the existing record using pid as the condition
                    $dataToUpdate = [];
                    foreach ($data as $key => $value) {
                        if (isset($isUserEdited->$key) && strcmp((string)$isUserEdited->$key, (string)$value) !== 0) {
                            $dataToUpdate[$key] = $value;
                        }
                    }
                    if (!empty($dataToUpdate)) {
                        $updated = $user->update($userId, $dataToUpdate, 'pid');
                        echo "Done updateing: ";
                    } else {
                        $updated = true; // No changes needed, consider it successful
                    }
                }
            }

            if ($updated) {
                $normalUser = new User();
                $normalUser->update($userId, [
                    'AccountStatus' => 2
                ], 'pid');
                // Update the user session data
                $_SESSION['user']->AccountStatus = 2;
                $status = "Profile update request sent successfully! Please wait for approval.";
            } else {
                $errors[] = "Failed to update profile. Please try again.";
            }
        }

        // Store errors or success in session and redirect
        if (!empty($errors)) {
            $errorString = implode("<br>", $errors);
            $_SESSION['flash']['msg'] = $errorString;
            $_SESSION['flash']['type'] = "error";
            // $_SESSION['errors'] = $errors;
            // $_SESSION['status'] = $status;
            redirect('dashboard/profile');
            exit;
        }
        $_SESSION['flash']['msg'] = $status;
        $_SESSION['flash']['type'] = "success";
        // $_SESSION['errors'] = $errors;
        // $_SESSION['status'] = $status;
        redirect('dashboard/profile');
        exit;
    }
    private function reportProblem($propertyId)
    {
        // Validate property ID
        $propertyId = (int)$propertyId;
        if (!$propertyId) {
            $_SESSION['flash']['msg'] = "Invalid property selected.";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/propertylisting');
            return;
        }

        $propertyModel = new PropertyConcat();
        $property = $propertyModel->where(['property_id' => $propertyId])[0] ?? null;

        if (!$property) {
            $_SESSION['flash']['msg'] = "Property not found.";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/propertylisting');
            return;
        }

        // If property status is pending, show warning
        if (isset($property->status) && strtolower($property->status) === 'pending') {
            $_SESSION['flash']['msg'] = "This property is pending approval. You cannot report a problem at this time.";
            $_SESSION['flash']['type'] = "warning";
            redirect('dashboard/propertylisting/propertyunitowner/'.$propertyId);
            return;
        }

        $agent = new User;   
        $agentDetails = $agent->where(['pid' => $property->agent_id])[0] ?? null;
        if (!$agentDetails) {
            $_SESSION['flash']['msg'] = "Agent not found.";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/propertylisting/propertyunitowner/'.$propertyId);
            return;
        }
        // Get agent full name
        $agentName = trim(($agentDetails->fname ?? '') . ' ' . ($agentDetails->lname ?? ''). ' ' . '[PID ' . $agentDetails->pid . ']');
        if (empty($agentName)) {
            $agentName = "Unknown Agent";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $description = trim($_POST['problem'] ?? '');
            $urgency = $_POST['urgency'] ?? '';
            $location = trim($_POST['location'] ?? '');
            $errors = [];

            // Validate fields
            if (empty($description)) {
                $errors[] = "Problem description is required.";
            }
            if (!in_array($urgency, ['1', '2', '3'])) {
                $errors[] = "Urgency level is required.";
            }
            // if (empty($location)) {
            //     $errors[] = "Location is required.";
            // }

            // Prepare data for ProblemReport model
            $problemData = [
                'problem_description' => $description,
                'problem_location' => $location,
                'urgency_level' => (int)$urgency,
                'property_id' => $propertyId,
                'status' => 'pending',
                'created_by' => $_SESSION['user']->pid
            ];

            $problemReport = new ProblemReport();
            if (!$problemReport->validate($problemData)) {
                $errors = array_merge($errors, $problemReport->errors);
            }

            if (empty($errors)) {
                // Insert the problem report (returns true/false)
                $inserted = $problemReport->insert($problemData);

                // Only proceed if insert was successful
                if ($inserted) {
                    // Fetch the last inserted report for this user and property
                    $lastReport = $problemReport->where([
                        'problem_description' => $description,
                        'problem_location' => $location,
                        'urgency_level' => (int)$urgency,
                        'property_id' => $propertyId,
                        'status' => 'pending',
                        'created_by' => $_SESSION['user']->pid
                    ]);
                    // Get the most recent one (assuming submitted_at DESC in your model)
                    $reportId = !empty($lastReport) ? $lastReport[0]->report_id : null;

                    // --- Use upload_image helper for images ---
                    if ($reportId && isset($_FILES['reports_images']) && $_FILES['reports_images']['error'][0] != 4) {
                        require_once __DIR__ . '/../core/functions.php'; // if not already loaded
                        $reportImageModel = new ProblemReportImage();
                        $targetDir = ROOTPATH . "public/assets/images/uploads/report_images/";

                        $imgErrors = upload_image(
                            $_FILES['reports_images'],
                            $targetDir,
                            $reportImageModel,
                            $reportId,
                            [
                                'allowed_ext' => ['jpg', 'jpeg', 'png'],
                                'prefix' => 'report_img',
                                'url_field' => 'image_url',
                                'fk_field' => 'report_id',
                                'max_size' => 5242880, // 5MB
                                'overwrite' => false
                            ]
                        );
                        if (!empty($imgErrors)) {
                            $errors = array_merge($errors, $imgErrors);
                        }
                    }

                    if (empty($errors)) {
                        $_SESSION['flash']['msg'] = "Problem reported successfully.";
                        $_SESSION['flash']['type'] = "success";
                        redirect('dashboard/propertylisting/propertyunitowner/' . $propertyId);
                        return;
                    }
                } else {
                    $errors[] = "Failed to create problem report.";
                }
            }

            // If errors, show them
            $_SESSION['flash']['msg'] = implode("<br>", $errors);
            $_SESSION['flash']['type'] = "error";
        }

        // Fetch problem reports with images for this property
        $problemReportView = new ProblemReportView();
        $reports = $problemReportView->getReportsWithImages($propertyId);

        $this->view('owner/reportProblem', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'agentName' => $agentName,
            'reports' => $reports, // Pass reports to the view
        ]);
        unset($_SESSION['errors']);
        unset($_SESSION['status']);
    }
    

    private function financialReportUnit($propertyId = null)
    {
        if (!$propertyId) {
            redirect('owner/financeReport');
            return;
        }

        // Get user details from the User model
        $userModel = new User();
        $userData = $userModel->where(['pid' => $_SESSION['user']->pid])[0] ?? null;

        // Get property details
        $property = new PropertyConcat;
        $propertyDetails = $property->where(['property_id' => $propertyId])[0] ?? null;

        if (!$propertyDetails) {
            $_SESSION['flash']['msg'] = "Property not found";
            $_SESSION['flash']['type'] = "error";
            redirect('owner/financeReport');
            return;
        }

        // Get agent details
        $agent = new User;
        $agentDetails = $agent->where(['pid' => $propertyDetails->agent_id])[0] ?? null;

        // Initialize variables before using them
        $totalIncome = 0;
        $activeBookings = 0;

        // Calculate monthly data for the last 6 months
        $currentMonth = date('n');
        $currentYear = date('Y');

        // Initialize monthly data structure
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = ($currentMonth - $i) > 0 ? ($currentMonth - $i) : (12 + ($currentMonth - $i));
            $year = ($currentMonth - $i) > 0 ? $currentYear : ($currentYear - 1);

            $monthName = date('M', mktime(0, 0, 0, $month, 1, $year));
            $monthlyData[$monthName] = [
                'income' => 0,
                'expense' => 0,
                'profit' => 0,
                'occupancy_rate' => 0
            ];
        }

        // Get all bookings/rentals (income) for this property
        $booking = new BookingModel();
        $bookings = $booking->where(['property_id' => $propertyId]);
        if (!is_array($bookings) && !is_object($bookings)) {
            $bookings = []; // Ensure $bookings is always iterable
        }

        // Now the foreach loop will work even if no bookings are found
        foreach ($bookings as $booking) {
            $bookingMonth = date('M', strtotime($booking->start_date));
            if (isset($monthlyData[$bookingMonth])) {
                $monthlyData[$bookingMonth]['income'] += $booking->price;
            }
            $totalIncome += $booking->price;

            if ((isset($booking->status) && strtolower($booking->status) === 'active') ||
                (isset($booking->accept_status) && strtolower($booking->accept_status) === 'accepted')
            ) {
                $activeBookings++;
            }
        }

        // Get all service logs (expenses) for this property
        $serviceLog = new ServiceLog();
        $serviceLogs = $serviceLog->where([
            'property_id' => $propertyId,
            'requested_person_id' => $_SESSION['user']->pid
        ]);
        
        if (!is_array($serviceLogs) && !is_object($serviceLogs)) {
            $serviceLogs = []; // Ensure $serviceLogs is always iterable
        }

        // Calculate monthly income and expenses for the last 6 months
        $currentMonth = date('n');
        $currentYear = date('Y');

        // Calculate total income from bookings
        $totalIncome = 0;
        $activeBookings = 0;
        foreach ($bookings as $booking) {
            $bookingMonth = date('M', strtotime($booking->start_date));
            if (isset($monthlyData[$bookingMonth])) {
                $monthlyData[$bookingMonth]['income'] += $booking->price;
            }
            $totalIncome += $booking->price;

            if ((isset($booking->status) && strtolower($booking->status) === 'active') ||
                (isset($booking->accept_status) && strtolower($booking->accept_status) === 'accepted')
            ) {
                $activeBookings++;
            }
        }

        // Calculate total expenses from service logs
        $totalExpenses = 0;
        foreach ($serviceLogs as $log) {
            $cost = $log->total_cost;
            $logMonth = date('M', strtotime($log->date));
            if (isset($monthlyData[$logMonth])) {
                $monthlyData[$logMonth]['expense'] += $cost;
            }
            $totalExpenses += $cost;
        }

        // Calculate profit and occupancy rate
        $occupancyRate = ($propertyDetails->units > 0) ?
            (($activeBookings / $propertyDetails->units) * 100) : 0;

        foreach ($monthlyData as $month => &$data) {
            $data['profit'] = $data['income'] - $data['expense'];
            // Simplified occupancy calculation (will be more accurate with real data)
            $data['occupancy_rate'] = isset($bookingCounts[$month]) && $propertyDetails->units > 0 ?
                ($bookingCounts[$month] / $propertyDetails->units) * 100 : $occupancyRate;
        }

        // Prepare data for the view
        $viewData = [
            'user' => $userData,  // Use the user data from the model
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'property' => $propertyDetails,
            'agent' => $agentDetails,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'profit' => $totalIncome - $totalExpenses,
            'occupancyRate' => $occupancyRate,
            'monthlyData' => $monthlyData,
            'bookings' => $bookings,
            'serviceLogs' => $serviceLogs
        ];

        $this->view('owner/financialReportUnit', $viewData);
    }

    public function showReviews()
    {
        $this->view('owner/showReviews', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    // drop property
    public function dropProperty($propertyId)
    {
        $property = new PropertyModel;
        if ($property->update($propertyId, ['status' => 'inactive'], 'property_id')) {
            $_SESSION['flash']['msg'] = 'Property dropped successfully!';
            $_SESSION['flash']['type'] = 'success';
        } else {
            $_SESSION['flash']['msg'] = 'Failed to drop property. Please try again.';
            $_SESSION['flash']['type'] = 'error';
        }
        // Redirect to the property listing page
        redirect('property/propertyListing');
    }



    // dont touch this..final create function for properties
    public function create(){
        $property = new Property;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate the form data
            if (!$property->validateProperty($_POST)) {
                $this->view('owner/addProperty', ['property' => $property]);
                return;
            }

            // Set default values
            $purpose = $_POST['purpose'] ?? 'Rent';
            $rental_period = $_POST['rental_period'] ?? 'Daily';
            $start_date = $_POST['start_date'] ?? date('Y-m-d');
            $end_date = $_POST['end_date'] ?? date('Y-m-d', strtotime('+7 days', strtotime($start_date)));

            // Calculate the rental price based on the purpose
            if ($purpose == 'Rent') {
                $rental_price = $_POST['rental_price'] ?? 0; // Assuming the rental price is directly posted for "Rent"
            } else {
                // Calculate duration in days for Safeguard/Vacation Rental purposes
                $start_timestamp = strtotime($start_date);
                $end_timestamp = strtotime($end_date);

                if ($start_timestamp && $end_timestamp) {
                    // Ensure end date is greater than start date
                    $duration_in_days = ($end_timestamp - $start_timestamp) / (60 * 60 * 24); // Duration in days

                    // If the end date is before the start date, set an error or fallback value
                    if ($duration_in_days <= 0) {
                        $duration_in_days = 1; // Minimum duration of 1 day
                    }

                    // Calculate rental price based on duration (using the constant RENTAL_PRICE)
                    $rental_price = $duration_in_days * RENTAL_PRICE;
                } else {
                    $rental_price = 0; // Default to 0 if invalid dates
                }
            }

            // Prepare property data for insertion
            $arr = [
                'name' => $_POST['name'],
                'type' => $_POST['type'] ?? 'House',
                'description' => $_POST['description'] ?? '',

                'address' => $_POST['address'] ?? '',
                'zipcode' => $_POST['zipcode'] ?? '',
                'city' => $_POST['city'] ?? '',
                'state_province' => $_POST['state_province'] ?? '',
                'country' => $_POST['country'] ?? 'Sri Lanka',

                'year_built' => $_POST['year_built'] ?? 2025,
                'size_sqr_ft' => $_POST['size_sqr_ft'] ?? 0,
                'number_of_floors' => $_POST['number_of_floors'] ?? 0,
                'floor_plan' => $_POST['floor_plan'] ?? 'no',

                'units' => $_POST['units'] ?? 0,
                'bedrooms' => $_POST['bedrooms'] ?? 0,
                'bathrooms' => $_POST['bathrooms'] ?? 0,
                'kitchen' => $_POST['kitchen'] ?? 'no',
                'living_room' => $_POST['living_room'] ?? 'no',

                'furnished' => $_POST['furnished'] ?? 'no',
                'furniture_description' => $_POST['furniture_description'] ?? '',

                'parking' => $_POST['parking'] ?? 'no',
                'parking_slots' => $_POST['parking_slots'] ?? 0,
                'type_of_parking' => $_POST['type_of_parking'] ?? 'none',

                'utilities_included' => isset($_POST['utilities_included']) && is_array($_POST['utilities_included']) ? implode(',', $_POST['utilities_included']) : ($_POST['utilities_included'] ?? ''),
                'additional_utilities' => isset($_POST['additional_utilities']) && is_array($_POST['additional_utilities']) ? implode(',', $_POST['additional_utilities']) : ($_POST['additional_utilities'] ?? ''),
                'additional_amenities' => isset($_POST['additional_amenities']) && is_array($_POST['additional_amenities']) ? implode(',', $_POST['additional_amenities']) : ($_POST['additional_amenities'] ?? ''),
                'security_features' => isset($_POST['security_features']) && is_array($_POST['security_features']) ? implode(',', $_POST['security_features']) : ($_POST['security_features'] ?? ''),

                'purpose' => $purpose,
                'rental_period' => $rental_period,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'rental_price' => $rental_price,

                'owner_name' => $_POST['owner_name'],
                'owner_email' => $_POST['owner_email'],
                'owner_phone' => $_POST['owner_phone'] ?? '',
                'additional_contact' => $_POST['additional_contact'],

                'special_instructions' => isset($_POST['special_instructions']) && is_array($_POST['special_instructions']) ? implode(',', $_POST['special_instructions']) : ($_POST['special_instructions'] ?? ''),
                'legal_details' => isset($_POST['legal_details']) && is_array($_POST['legal_details']) ? implode(',', $_POST['legal_details']) : ($_POST['legal_details'] ?? ''),

                'status' => 'pending',
                'person_id' => $_SESSION['user']->pid,
                'agent_id' => MANAGER_ID,
                'duration' => $_POST['duration'] ?? 1
            ];

            // Insert property data into the database
            //$res = $property->insert($arr);

            // Debugging: Check if the data is prepared correctly
            //error_log("Prepared Data: " . print_r($arr, true));

            $res = $property->insert($arr);

            // if (!$res) {
            //     error_log("Insert function failed!");
            // }

            if ($res) {
                // Get the ID of the last inserted property
                $propertyId = $property->where(['name' => $_POST['name'], 'address' => $_POST['address']])[0]->property_id;

                // Upload images using the generic function
                $imageErrors = upload_image(
                    $_FILES['property_images'],
                    ROOTPATH . 'public/assets/images/uploads/property_images/',
                    new PropertyImageModel(),
                    $propertyId,
                    [
                        'allowed_ext' => ['jpg', 'jpeg', 'png'],
                        'prefix' => 'property',
                        'url_field' => 'image_url',
                        'fk_field' => 'property_id'
                    ]
                );

                // Upload documents using the same function with different config
                $documentErrors = upload_image(
                    $_FILES['property_documents'],
                    ROOTPATH . 'public/assets/documents/uploads/property_documents/',
                    new PropertyDocModel(),
                    $propertyId,
                    [
                        'allowed_ext' => ['pdf', 'docx', 'txt'],
                        'prefix' => 'doc',
                        'url_field' => 'image_url',
                        'fk_field' => 'property_id',
                        'max_size' => 10 * 1024 * 1024 // 10MB
                    ]
                );

                // Check for any upload errors
                if (!empty($imageErrors) || !empty($documentErrors)) {
                    $property->errors['media'] = array_merge($imageErrors, $documentErrors);
                    $_SESSION['flash']['msg'] = "Property added failed!";
                    $_SESSION['flash']['type'] = "error";
                    $this->view('owner/addProperty', ['property' => $property]);
                    return;
                }

                // Redirect on success
                $_SESSION['flash']['msg'] = "Property added successfully!";
                $_SESSION['flash']['type'] = "success";
                //enqueueNotification('Property Added', 'New property has been added!', 'dashboard/managementhome/propertymanagement/assignagents', 'Notification_green', MANAGER_ID);
                //enqueueNotification('Property Added', 'Your property has been added successfully.', '', 'Notification_green');
                redirect('property/propertyListing');
            } else {
                enqueueNotification('Property Addition Failed', 'Failed to add property. Please try again.', '', 'Notification_red');
                $property->errors['insert'] = 'Failed to add Property. Please try again.';
                $this->view('property/propertyListing', ['property' => $property]);
            }
        } else {
            enqueueNotification('Property Creation Failed', 'Failed to create property. Please try again.', '', 'Notification_red');
            $this->view('property/propertyListing', ['property' => $property]);
        }
    }

    public function updatePending($data, $propertyId)
    {
        $property = new Property;
        $res = $property->update($propertyId, $data, 'property_id');
        if ($res) {
            enqueueNotification('Property Updated', 'Your property has been updated successfully.', '', 'Notification_green');
            $_SESSION['flash']['msg'] = "Property updated successfully!";
            $_SESSION['flash']['type'] = "success";
        } else {
            enqueueNotification('Property Update Failed', 'Failed to update property. Please try again.', '', 'Notification_red');
            $_SESSION['flash']['msg'] = "Failed to update property. Please try again.";
            $_SESSION['flash']['type'] = "error";
        }
        // Redirect to the property listing page
        redirect('property/propertyListing');
    }

    public function update($propertyId)
    {
        $property = new PropertyModelTemp;
        $beforeDetails = new Property;
        $beforeDetails = $beforeDetails->where(['property_id' => $propertyId])[0];

        // Helper to safely implode or fallback
        $safeImplode = function ($value) {
            if (is_array($value)) {
                return implode(',', $value);
            } elseif (is_string($value)) {
                return $value; // Assume already comma-separated
            }
            return '';
        };

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$property->validateProperty($_POST)) {
                $_SESSION['flash']['msg'] = "Update Validation failed!";
                $_SESSION['flash']['type'] = "error";
                $this->view('owner/editPropertyEnh', ['property' => $beforeDetails]);
                return;
            }

            $purpose = $_POST['purpose'] ?? $beforeDetails->purpose;
            $rental_period = $_POST['rental_period'] ?? $beforeDetails->rental_period;
            $start_date = $_POST['start_date'] ?? $beforeDetails->start_date;
            $end_date = $_POST['end_date'] ?? $beforeDetails->end_date;

            if ($purpose == 'Rent') {
                $rental_price = $_POST['rental_price'] ?? $beforeDetails->rental_price;
            } else {
                $start_timestamp = strtotime($start_date);
                $end_timestamp = strtotime($end_date);
                $duration_in_days = ($start_timestamp && $end_timestamp) ? max(1, ($end_timestamp - $start_timestamp) / (60 * 60 * 24)) : 1;
                $rental_price = $duration_in_days * RENTAL_PRICE;
            }

            $arr = [
                'property_id' => $propertyId,
                'name' => $_POST['name'] ?? $beforeDetails->name,
                'type' => $_POST['type'] ?? $beforeDetails->type,
                'description' => $_POST['description'] ?? $beforeDetails->description,
                'address' => $_POST['address'] ?? $beforeDetails->address,
                'zipcode' => $_POST['zipcode'] ?? $beforeDetails->zipcode,
                'city' => $_POST['city'] ?? $beforeDetails->city,
                'state_province' => $_POST['state_province'] ?? $beforeDetails->state_province,
                'country' => $_POST['country'] ?? $beforeDetails->country,

                'year_built' => $_POST['year_built'] ?? $beforeDetails->year_built,
                'size_sqr_ft' => $_POST['size_sqr_ft'] ?? $beforeDetails->size_sqr_ft,
                'number_of_floors' => $_POST['number_of_floors'] ?? $beforeDetails->number_of_floors,
                'floor_plan' => $_POST['floor_plan'] ?? $beforeDetails->floor_plan,

                'units' => $_POST['units'] ?? $beforeDetails->units,
                'bedrooms' => $_POST['bedrooms'] ?? $beforeDetails->bedrooms,
                'bathrooms' => $_POST['bathrooms'] ?? $beforeDetails->bathrooms,
                'kitchen' => $_POST['kitchen'] ?? $beforeDetails->kitchen,
                'living_room' => $_POST['living_room'] ?? $beforeDetails->living_room,

                'furnished' => $_POST['furnished'] ?? $beforeDetails->furnished,
                'furniture_description' => $_POST['furniture_description'] ?? $beforeDetails->furniture_description,

                'parking' => $_POST['parking'] ?? $beforeDetails->parking,
                'parking_slots' => $_POST['parking_slots'] ?? $beforeDetails->parking_slots,
                'type_of_parking' => $_POST['type_of_parking'] ?? $beforeDetails->type_of_parking,

                'utilities_included' => $safeImplode($_POST['utilities_included'] ?? $beforeDetails->utilities_included),
                'additional_utilities' => $safeImplode($_POST['additional_utilities'] ?? $beforeDetails->additional_utilities),
                'additional_amenities' => $safeImplode($_POST['additional_amenities'] ?? $beforeDetails->additional_amenities),
                'security_features' => $safeImplode($_POST['security_features'] ?? $beforeDetails->security_features),

                'purpose' => $purpose,
                'rental_period' => $rental_period,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'rental_price' => $rental_price,

                'owner_name' => $_POST['owner_name'] ?? $beforeDetails->owner_name,
                'owner_email' => $_POST['owner_email'] ?? $beforeDetails->owner_email,
                'owner_phone' => $_POST['owner_phone'] ?? $beforeDetails->owner_phone,
                'additional_contact' => $_POST['additional_contact'] ?? $beforeDetails->additional_contact,

                'special_instructions' => $safeImplode($_POST['special_instructions'] ?? $beforeDetails->special_instructions),
                'legal_details' => $safeImplode($_POST['legal_details'] ?? $beforeDetails->legal_details),

                'status' => $beforeDetails->status,
                'person_id' => $beforeDetails->person_id,
                'agent_id' => $beforeDetails->agent_id,
                'duration' => ($purpose == 'Rent') ? ($_POST['duration'] ?? $beforeDetails->duration) : 0
            ];

            // ✅ Check if status is pending and directly update real table
            if ($beforeDetails->status === 'Pending') {
                $this->updatePending($arr, $propertyId); // function inside same controller
                return;
            }

            // Continue temp table handling for non-pending properties
            $arr['request_status'] = 'pending'; // Only needed in temp table

            $beforeDetailsAsArray = (array)$beforeDetails;
            $detect_change = $property->compareWithPrevios($arr, $beforeDetailsAsArray);

            if ($detect_change) {
                $existingProperty = $property->where(['property_id' => $propertyId]);
                echo "checkpoint 1";
                if (!empty($existingProperty)) {
                    $property->delete($propertyId, 'property_id');
                }
                echo "checkpoint 2";

                $res = $property->insert($arr);

                echo "checkpoint 3";

                if ($res) {
                    if (isset($_FILES['property_images']) && $_FILES['property_images']['error'] === 0 && $_FILES['property_images']['size'] > 0) {
                        $imageErrors = upload_image(
                            $_FILES['property_images'],
                            ROOTPATH . 'public/assets/images/uploads/property_images/',
                            new PropertyImageModelTemp(),
                            $propertyId,
                            [
                                'allowed_ext' => ['jpg', 'jpeg', 'png'],
                                'prefix' => 'property',
                                'url_field' => 'image_url',
                                'fk_field' => 'property_id'
                            ]
                        );

                        if (!empty($imageErrors)) {
                            $property->errors['media'] = $imageErrors;
                            $_SESSION['flash']['msg'] = "Property update failed! Error with image upload.";
                            $_SESSION['flash']['type'] = "error";
                            $this->view('owner/editPropertyEnh', ['property' => $beforeDetails]);
                            return;
                        }
                    }

                    $_SESSION['flash']['msg'] = "Property Update Request Sent!";
                    $_SESSION['flash']['type'] = "success";
                    enqueueNotification('Property Update Request', 'Your property update request has been sent successfully.', '', 'Notification_green');
                    enqueueNotification('Property Update Request', 'A property update request has been sent for your property.', ROOT . '/dashboard/property/comparePropertyUpdate/' . $beforeDetails->property_id, 'Notification_green', $beforeDetails->agent_id);
                    redirect('property/propertyListing');
                } else {
                    $_SESSION['flash']['msg'] = "Property update failed!";
                    $_SESSION['flash']['type'] = "error";
                    $this->view('owner/editPropertyEnh', ['property' => $beforeDetails]);
                }
            } else {
                $_SESSION['flash']['msg'] = "There is No change!";
                $_SESSION['flash']['type'] = "info";
                redirect('dashboard/propertylisting');
            }
        }
    }








    public function deleteRequest($propertyId)
    {
        $propertyInstanse = new Property;
        $property = $propertyInstanse->where(['property_id' => $propertyId])[0];
        if ($property->status == 'Pending') {
            $updated = $propertyInstanse->update($propertyId, ['status' => 'Inactive'], 'property_id');
            if ($updated) {
                $_SESSION['flash']['msg'] = "Property deleted successfully!";
                $_SESSION['flash']['type'] = "success";
                enqueueNotification('Property Deletion', 'Your property has been deleted successfully.', '', 'Notification_green');
            } else {
                $_SESSION['flash']['msg'] = "Failed to delete property. Please try again.";
                $_SESSION['flash']['type'] = "error";
                enqueueNotification('Property Deletion Failed', 'Failed to delete property. Please try again.', '', 'Notification_red');
            }
            redirect('property/propertyListing');
        } elseif ($property->status == 'Occupied') {
            $_SESSION['flash']['msg'] = "You cannot delete an occupied property!";
            $_SESSION['flash']['type'] = "error";
            enqueueNotification('Property Deletion Failed', 'You cannot delete an Occupied property. Wait until Rental period is over!', '', 'Notification_red');
            redirect('property/propertyListing');
        } elseif ($property->status == 'Active') {
            $deleteRequest = new DeleteRequests;
            $ownerId = $property->person_id;
            $agentId = $property->agent_id;
            $deleteRequest->insert([
                'property_id' => $propertyId,
                'owner_id' => $ownerId,
                'agent_id' => $agentId,
                'request_status' => 'Pending',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            if ($deleteRequest) {
                $_SESSION['flash']['msg'] = "Property deletion request sent successfully!";
                $_SESSION['flash']['type'] = "success";
                enqueueNotification('Property Deletion Request', 'Your property deletion request has been sent successfully.', ROOT . '/dashboard/propertyListing/propertyunitowner/' . $propertyId, 'Notification_green');
                enqueueNotification('Property Deletion Request', 'A property deletion request has been sent for your property.', ROOT . '/dashboard/property/removalRequests', 'Notification_green', $agentId);
            } else {
                $_SESSION['flash']['msg'] = "Failed to send property deletion request. Please try again.";
                $_SESSION['flash']['type'] = "error";
                enqueueNotification('Property Deletion Request Failed', 'Failed to send property deletion request. Please try again.', '', 'Notification_red');
            }
            redirect('property/propertyListing');
        }
    }

    public function payment($serviceId = '')
    {
        // Check if service ID is provided
        if (empty($serviceId)) {
            redirect('/dashboard/maintenance');
            return;
        }

        // Get service details
        $serviceLog = new ServiceLog();
        $serviceDetails = $serviceLog->first(['service_id' => $serviceId]);

        if (!$serviceDetails) {
            $_SESSION['flash']['msg'] = "Service not found";
            $_SESSION['flash']['type'] = "error";
            redirect('/dashboard/maintenance');
            return;
        }

        // Pass the service details to the payment view
        $this->view('owner/payment', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'serviceLog' => $serviceDetails
        ]);
    }

    public function trackOrder($propertyId)
    {
            // Get the current user's ID
        $ownerId = $_SESSION['user']->pid ?? 0;
        
        // Get property details
        $property = new PropertyConcat;
        $propertyDetails = $property->where(['property_id' => $propertyId])[0];
        // Get service logs for this property AND requested by the current user
        $serviceLog = new ServiceLog();
        $serviceLogs = $serviceLog->where([
            'property_id' => $propertyId,
            'requested_person_id' => $ownerId
        ]);
        
        if (!$serviceLogs) {
            $serviceLogs = [];
        }
        
        // Apply status filtering
        if (!empty($_GET['status_filter'])) {
            $status = $_GET['status_filter'];
            $filteredLogs = [];
            foreach ($serviceLogs as $log) {
                if (strtolower($log->status) == strtolower($status)) {
                    $filteredLogs[] = $log;
                }
            }
            $serviceLogs = $filteredLogs;
        }

        // Apply date range filtering
        if (!empty($_GET['date_from']) || !empty($_GET['date_to'])) {
            $dateFrom = !empty($_GET['date_from']) ? strtotime($_GET['date_from']) : null;
            $dateTo = !empty($_GET['date_to']) ? strtotime($_GET['date_to'] . ' 23:59:59') : null;

            $filteredLogs = [];
            foreach ($serviceLogs as $log) {
                $logDate = strtotime($log->date);

                // Check if the log date is within the specified range
                $includeLog = true;
                if ($dateFrom && $logDate < $dateFrom) $includeLog = false;
                if ($dateTo && $logDate > $dateTo) $includeLog = false;

                if ($includeLog) {
                    $filteredLogs[] = $log;
                }
            }
            $serviceLogs = $filteredLogs;
        }

        // Apply sorting
        if (!empty($_GET['sort'])) {
            $sort = $_GET['sort'];

            usort($serviceLogs, function ($a, $b) use ($sort) {
                switch ($sort) {
                    case 'date_asc':
                        return strtotime($a->date) - strtotime($b->date);
                    case 'date_desc':
                        return strtotime($b->date) - strtotime($a->date);
                    default:
                        return strtotime($b->date) - strtotime($a->date); // Default: newest first
                }
            });
        }

        // Calculate total cost and pending count
        $totalCost = 0;
        $pendingCount = 0;
        foreach ($serviceLogs as $log) {
            $totalCost += ($log->cost_per_hour * $log->total_hours);
            if (strtolower($log->status) == 'pending' || strtolower($log->status) == 'in progress') {
                $pendingCount++;
            }
        }

        $this->view('owner/trackOrder', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'property' => $propertyDetails,
            'serviceLogs' => $serviceLogs,
            'totalCost' => $totalCost,
            'pendingCount' => $pendingCount
        ]);
    }


    public function payAdvance($property_id)
    {
        $property = new Property;
        $propertyDetails = $property->where(['property_id' => $property_id])[0];
        if (!$propertyDetails) {
            $_SESSION['flash']['msg'] = "Property not found";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/propertyListing');
            return;
        }
        if ($propertyDetails->person_id != $_SESSION['user']->pid) {
            $_SESSION['flash']['msg'] = "You are not authorized to view this property";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/propertyListing');
            return;
        }


        $advance_price = findAdvancePrice($propertyDetails->rental_price);
        $payment = [
            'payment_name' => 'Property Advance Payment',
            'property_id' => $property_id,
            'fname' => $_SESSION['user']->fname,
            'lname' => $_SESSION['user']->lname,
            'email' => $_SESSION['user']->email,
            'phone' => $_SESSION['user']->contact,
            'amount' => $advance_price,
            'order_id' => uniqid('PropertyAdvance_'),
            'currency' => 'LKR',
            'item' => 'Property Advance Payment on ' . $propertyDetails->name,
            'address' => $propertyDetails->address,
            'city' => $propertyDetails->city,
            'return_url' => ROOT . '/dashboard/advancePaymentStatus/success/' . $property_id,
            'cancel_url' => ROOT . '/dashboard/advancePaymentStatus/failed/' . $property_id,
        ];

        $this->view('paymentGateway', ['payment' => $payment, 'property' => $propertyDetails]);
    }

    public function advancePaymentStatus($status, $property_id)
    {
        $property = new Property;
        $propertyDetails = $property->where(['property_id' => $property_id])[0];
        if (!$propertyDetails) {
            $_SESSION['flash']['msg'] = "Property not found";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/propertyListing/propertyunitowner/37' . $property_id);
            return;
        }
        if ($propertyDetails->person_id != $_SESSION['user']->pid) {
            $_SESSION['flash']['msg'] = "You are not authorized to view this property";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/propertyListing/propertyunitowner/' . $property_id);
            return;
        }

        if ($status == 'success') {
            if (!$property_id) {
                $_SESSION['flash']['msg'] = "Property not found";
                $_SESSION['flash']['type'] = "error";
                redirect('dashboard/propertyListing/propertyunitowner/' . $property_id);
                return;
            }
            $property = new Property;
            $newRentalPrice = $propertyDetails->rental_price - findAdvancePrice($propertyDetails->rental_price);
            $updateAdvancePayment = $property->update($property_id, ['advance_paid' => 'Paid', 'rental_price' => $newRentalPrice], 'property_id');
            if (!$updateAdvancePayment) {
                $_SESSION['flash']['msg'] = "Failed to update advance payment status. Please try again.";
                $_SESSION['flash']['type'] = "error";
                redirect('dashboard/propertyListing/propertyunitowner/' . $property_id);
                return;
            }
            $sharePayment = new SharePayment;
            $sharePayment->insert([
                'person_id' => $_SESSION['user']->pid,
                'property_id' => $property_id,
                'amount' => findAdvancePrice($propertyDetails->rental_price),
                'payment_date' => date('Y-m-d H:i:s'),
                'rental_period_start' => $propertyDetails->start_date,
                'rental_period_end' => $propertyDetails->end_date,
                'transaction_id' => $_POST['order_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'payment_type' => 'advance'
            ]);
            if (!$sharePayment) {
                $_SESSION['flash']['msg'] = "Failed to record advance payment. Please try again.";
                $_SESSION['flash']['type'] = "error";
                redirect('dashboard/propertyListing/propertyunitowner/' . $property_id);
                return;
            }
            // Payment was successful
            enqueueNotification('Advance Payment Success', 'Your advance payment has been successfully processed on property' . $propertyDetails->name, '', 'Notification_green');
            enqueueNotification('Advance Payment Success', 'An advance payment has been successfully processed for your property.', ROOT . '/dashboard/preInspection', 'Notification_green', $propertyDetails->agent_id);
            $_SESSION['flash']['msg'] = "Payment successful!";
            $_SESSION['flash']['type'] = "success";
        } else {
            // Payment failed or was cancelled
            enqueueNotification('Advance Payment Failed', 'Your advance payment has failed or was cancelled.', '', 'Notification_red');
            $_SESSION['flash']['msg'] = "Payment failed or cancelled!";
            $_SESSION['flash']['type'] = "error";
        }

        redirect('dashboard/propertyListing');
    }
}
