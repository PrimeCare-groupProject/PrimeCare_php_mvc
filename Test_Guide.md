# ✅ PHPUnit Testing Guide for Our PHP MVC Project

## 📁 Project Directory Structure

Our base project is structured as follows:

```
/app
    /controllers
    /models
    /views
    /core
/helpers
/tests
    /Unit
        NotificationTest.php
/vendor
composer.json
phpunit.xml
```

---

## ⚙️ 1. Setup Instructions

### 📌 Install PHPUnit via Composer

Run the following in your project root:

```bash
composer require --dev phpunit/phpunit ^10
```

This installs PHPUnit as a development dependency.

To run tests, use:

```bash
./vendor/bin/phpunit
```

---

## 🧪 2. Creating a Test

### ✅ Where to Place Tests

- All test files go inside the `/tests/Unit/` directory.
- File names must end with `Test.php`, for example: `NotificationTest.php`.

### ✅ Structure of a Test File

```php
<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../app/helpers/YourHelper.php'; // or model/controller/etc.

class YourHelperTest extends TestCase
{
    public function testSomeFunctionality()
    {
        $result = yourHelperFunction();
        $this->assertEquals('expected value', $result);
    }
}
```

---

## 🛠️ 3. Helper Function Testing

### 📌 Example: `enqueue()` and `dequeue()` from NotificationQueueHelper

```php
public function testEnqueue_AddsToFront() {
    $queue = [
        ['notification_id' => 1],
        ['notification_id' => 2]
    ];

    enqueue(['notification_id' => 3], $queue);

    $this->assertEquals(3, $queue[0]['notification_id']);
    $this->assertEquals(1, $queue[1]['notification_id']);
}

public function testDequeue_ReturnsLastItem() {
    $queue = [
        ['notification_id' => 1],
        ['notification_id' => 2],
        ['notification_id' => 3]
    ];

    $id = dequeue($queue);

    $this->assertEquals(3, $id);
    $this->assertCount(2, $queue);
}
```

---

## 🧪 4. Testing Database-Dependent Logic (Optional)

### ✅ Good Practice

For logic involving the database (e.g., `NotificationModel`), it’s better to **extract logic into helpers** and test those helpers in isolation.

If database testing is needed:

- Use a **separate test database**.
- Make sure your test cleans up after itself using `setUp()` and `tearDown()`:

```php
protected function setUp(): void {
    $this->model = new NotificationModel();
    // Clear test data
}

protected function tearDown(): void {
    // Clean test data
}
```

---

## 🚀 5. Running the Tests

Run all tests from project root:

```bash
php ./vendor/bin/phpunit tests/Unit
```

Or just:

```bash
php ./vendor/bin/phpunit
```

If `phpunit.xml` is configured.

Expected output:

```
PHPUnit 10.x by Sebastian Bergmann and contributors.

..                                                                  2 / 2 (100%)

Time: 00:00.123, Memory: 6.00 MB

OK (2 tests, 4 assertions)
```

---

## 🧼 6. Best Practices

- Use `require_once` to load helpers/models directly (autoloading not used).
- One test = one behavior.
- Use `$_SESSION` or mock data directly in test methods.
- Avoid testing production DB content.
- Name test methods clearly, e.g. `testEnqueueNotifications_AddsToDatabase()`.

---

## 💬 FAQ

**Q: I get “Access Denied” errors when I include model files?**  
A: This may be due to Windows file access issues. Try isolating logic into helper functions and test those instead.

**Q: How do I test session data like `$_SESSION['user']`?**  
A: Just mock it in your test:

```php
$_SESSION['user'] = (object)['pid' => 9999];
```

---

## 📎 Useful Commands

| Command                        | Description                        |
|-------------------------------|------------------------------------|
| `composer install`            | Install dependencies               |
| `./vendor/bin/phpunit`        | Run all tests                      |
| `phpunit tests/Unit/File.php` | Run specific test file             |

---

Happy Testing! ✅
