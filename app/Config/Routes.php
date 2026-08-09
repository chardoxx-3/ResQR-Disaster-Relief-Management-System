<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Public / Auth Routes
$routes->get('/', 'Auth::index');
$routes->get('auth', 'Auth::index');
$routes->post('auth/authenticate', 'Auth::authenticate');
$routes->get('auth/logout', 'Auth::logout');

// Resident Portal (Public Access)
$routes->group('residentportal', function($routes) {
    $routes->get('/', 'ResidentPortal::index');
    $routes->post('verify', 'ResidentPortal::verify');
    $routes->get('generateQR/(:num)', 'ResidentPortal::generateQR/$1');
});

// Admin & Distributor Dashboard
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

// Beneficiary Management (Admin only)
$routes->group('beneficiaries', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Beneficiaries::index');
    $routes->get('add', 'Beneficiaries::add'); // Loads the form view
    $routes->get('resident-list', 'Beneficiaries::residentList');
    $routes->post('store', 'Beneficiaries::store');
    $routes->get('edit/(:num)', 'Beneficiaries::edit/$1');
    $routes->post('update/(:num)', 'Beneficiaries::update/$1');
    $routes->get('delete/(:num)', 'Beneficiaries::delete/$1');
    $routes->get('import', 'Beneficiaries::import');
    $routes->post('upload', 'Beneficiaries::upload');
    $routes->get('view/(:num)', 'Beneficiaries::view/$1');
    $routes->get('export', 'Beneficiaries::export');
    $routes->get('export-selected/(:segment)', 'Beneficiaries::exportSelected/$1');
    $routes->get('export-excel', 'Beneficiaries::exportExcel');
$routes->get('export-excel-selected/(:segment)', 'Beneficiaries::exportExcelSelected/$1');
$routes->post('delete-all', 'Beneficiaries::deleteAll');
// Add this line in your Beneficiaries routes group
$routes->get('get-all-ids', 'Beneficiaries::getAllIds');
$routes->get('check-duplicate-name', 'Beneficiaries::checkDuplicateName');
$routes->get('download-images', 'Beneficiaries::downloadImages');

// Add this inside the beneficiaries group
$routes->get('check-duplicates', 'Beneficiaries::checkDuplicates');
$routes->post('keep-record', 'Beneficiaries::keepRecord');
$routes->post('delete-record', 'Beneficiaries::deleteRecord');
$routes->post('delete-all-duplicates', 'Beneficiaries::deleteAllDuplicates');
$routes->get('image-report', 'Beneficiaries::imageReport');
$routes->get('image-report/export', 'Beneficiaries::exportImageReport');
$routes->get('image-report', 'Beneficiaries::imageReport');

$routes->get('print-list', 'Beneficiaries::printList');
$routes->get('get-streets', 'Beneficiaries::getStreets');
$routes->get('print-image-report', 'Beneficiaries::printImageReport');
});

// Inventory Management (Admin only)
$routes->group('inventory', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Inventory::index');
    $routes->get('add_item', 'Inventory::add_item');
    $routes->post('saveItem', 'Inventory::saveItem');
    $routes->post('saveItemWithBatch', 'Inventory::saveItemWithBatch');
    $routes->post('saveCookedFood', 'Inventory::saveCookedFood');
    $routes->get('batch/view/(:num)', 'Inventory::viewBatch/$1');  // ← Add this line
    $routes->get('update_stock/(:num)', 'Inventory::update_stock/$1');
    $routes->post('addStock', 'Inventory::addStock');
    $routes->get('batches/(:num)', 'Inventory::batches/$1');
    $routes->get('delete/(:num)', 'Inventory::delete/$1');
    $routes->get('get-batch-tracking/(:num)', 'Inventory::getBatchTracking/$1');
});

// Distribution / Scanning (Distributors & Admins)
$routes->group('distribution', ['filter' => 'auth'], function($routes) {
    $routes->get('scanner', 'Distribution::scanner');
    $routes->get('processScan', 'Distribution::processScan'); // Called via AJAX or URL
    $routes->get('process-scan-view', 'Distribution::processScanView'); // ✅ CORRECT - will become /distribution/process-scan-view
    $routes->get('today-stats', 'Distribution::todayStats'); // ✅ CORRECT - will become /distribution/today-stats
    $routes->get('get-distributor-info/(:num)/(:num)', 'Distribution::getDistributorInfo/$1/$2');
    $routes->get('list-json', 'Distribution::listJson');
});

// Reporting (Admin only)
$routes->group('reports', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Reports::index');
    $routes->get('inventory', 'Reports::inventoryReport');
    $routes->get('export', 'Reports::export_view');  // For the print/PDF view
$routes->post('export', 'Reports::export');      // For form submission

 $routes->get('missing-images', 'Reports::missingImages');
});

// User Management (Admin only)
$routes->group('users', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Users::index');
    $routes->get('create', 'Users::createPage'); // NEW: Show create form page
    $routes->post('create', 'Users::create');    // Keep this for form submission
});

// Scanner Settings
$routes->group('scanner', ['filter' => 'auth'], function($routes) {
    $routes->get('settings', 'Scanner::settings');
    $routes->post('save-camera-settings', 'Scanner::saveCameraSettings');
    $routes->post('save-hardware-settings', 'Scanner::saveHardwareSettings');
    $routes->post('test-connection', 'Scanner::testConnection');
});

// Attendance Routes (Admin only)
$routes->group('attendance', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Attendance::index');
    $routes->get('scanner', 'Attendance::scanner');
    $routes->post('qr-checkin', 'Attendance::qrCheckIn');
    $routes->get('qr-checkin-redirect', 'Attendance::qrCheckinRedirect');
    $routes->get('process-scan', 'Attendance::processScan'); // <-- ADD THIS NEW ROUTE
    $routes->get('manual-checkin', 'Attendance::manualCheckIn');
    $routes->post('manual-checkin', 'Attendance::manualCheckIn');
    $routes->get('checkout/(:num)', 'Attendance::checkOut/$1');
    $routes->get('export', 'Attendance::export');
    $routes->get('stats', 'Attendance::stats');
    $routes->get('beneficiaries', 'Attendance::beneficiaries');
    $routes->get('list-json', 'Attendance::listJson');
});
// Dispatch Management (Admin only)
$routes->group('dispatch', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Dispatch::index');                    // Batches in warehouse
    $routes->get('in-transit', 'Dispatch::inTransit');       // Batches in transit
    $routes->get('received', 'Dispatch::received');          // Batches received
    $routes->get('dispatch/(:num)', 'Dispatch::dispatchForm/$1'); // Dispatch form
    $routes->post('process-dispatch', 'Dispatch::processDispatch');
    $routes->get('receive/(:num)', 'Dispatch::receiveForm/$1'); // Receive form
    $routes->post('process-receipt', 'Dispatch::processReceipt');
    $routes->get('track/(:num)', 'Dispatch::track/$1');      // Tracking history
    $routes->post('scan-for-transit', 'Dispatch::scanForTransit');  // Remove dispatch/ prefix
    $routes->post('scan-for-receipt', 'Dispatch::scanForReceipt');  // Remove dispatch/ prefix
});

// Event Management (Admin only)
$routes->group('events', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Event::index');
    $routes->get('create', 'Event::create');
    $routes->post('store', 'Event::store');
    $routes->get('edit/(:num)', 'Event::edit/$1');
    $routes->post('update/(:num)', 'Event::update/$1');
    $routes->get('set-active/(:num)', 'Event::setActive/$1');
    $routes->get('close/(:num)', 'Event::close/$1');
    $routes->get('delete/(:num)', 'Event::delete/$1');
    $routes->get('stats/(:num)', 'Event::stats/$1');
    $routes->get('stats-detail/(:num)', 'Event::statsDetail/$1');
    $routes->get('suggest-evacuation-centers', 'Event::suggestEvacuationCenters');
});

// Add these routes for family member QR codes
$routes->get('residentportal/generate-member-qr/(:num)/(:num)', 'Beneficiaries::generateMemberQR/$1/$2');
$routes->get('residentportal/view-member-qr/(:num)/(:num)', 'Beneficiaries::viewMemberQR/$1/$2');
$routes->get('residentportal/print-member-qr/(:num)/(:num)', 'Beneficiaries::printMemberQR/$1/$2');

// Add this route for household QR
$routes->get('beneficiaries/view-household-qr/(:num)', 'Beneficiaries::viewHouseholdQR/$1');

$routes->get('reports/barangay-summary', 'Reports::barangarySummary');

$routes->get('barangay-summary/export', 'Reports::exportBarangaySummary');

$routes->get('reports/barangay-summary/print', 'Reports::printBarangaySummary');
$routes->get('reports/barangay-summary/export', 'Reports::exportBarangaySummary'); // Keep CSV export if needed


// Smart Mobile Kitchen (Distributors & Admins) — no attendance check, no FACED/household QR
$routes->group('smart-kitchen', ['filter' => 'auth'], function($routes) {
    $routes->get('scanner', 'SmartKitchen::scanner');
    $routes->get('processScan', 'SmartKitchen::processScan');
    $routes->get('today-stats', 'SmartKitchen::todayStats');
    $routes->get('list-json', 'SmartKitchen::listJson');
$routes->get('monitor', 'SmartKitchen::monitor'); // ← ADD THIS LINE
    $routes->get('guest-qr', 'SmartKitchen::guestQR');
    $routes->get('recent-activity', 'SmartKitchen::recentActivity'); // NEW
});