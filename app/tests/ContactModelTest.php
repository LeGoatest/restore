<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Core\Database;
use App\Core\Migration;
use App\Models\Contact;
use App\DTOs\ContactDTO;

class ContactModelTest extends TestCase
{
    protected function setUp(): void
    {
        Database::init(':memory:');
        Migration::init();
        Migration::run();

        // Seed some test data
        Database::insert('contacts', ['name' => 'John Doe', 'email' => 'john@example.com', 'status' => 'new', 'created_at' => '2023-01-01 10:00:00']);
        Database::insert('contacts', ['name' => 'Jane Doe', 'email' => 'jane@example.com', 'status' => 'read', 'created_at' => '2023-01-02 10:00:00']);
        Database::insert('contacts', ['name' => 'Peter Pan', 'email' => 'peter@example.com', 'status' => 'new', 'created_at' => '2023-01-03 10:00:00']);
    }

    protected function tearDown(): void
    {
        // No need to tear down in-memory database
    }

    public function testFind()
    {
        $contact = Contact::find(1);
        $this->assertInstanceOf(ContactDTO::class, $contact);
        $this->assertEquals('John Doe', $contact->name);
    }

    public function testAll()
    {
        $contacts = Contact::all();
        $this->assertCount(3, $contacts);
        $this->assertInstanceOf(ContactDTO::class, $contacts[0]);
    }

    public function testGetByStatus()
    {
        $contacts = Contact::getByStatus('new');
        $this->assertCount(2, $contacts);
        $this->assertInstanceOf(ContactDTO::class, $contacts[0]);
    }

    public function testGetRecent()
    {
        $contacts = Contact::getRecent(2);
        $this->assertCount(2, $contacts);
        $this->assertInstanceOf(ContactDTO::class, $contacts[0]);
        $this->assertEquals('Peter Pan', $contacts[0]->name);
    }
}
