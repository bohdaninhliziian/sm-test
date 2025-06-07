<?php

require_once 'SortedLinkedList.php';
require_once 'ListType.php';
require_once 'Exception/TypeException.php';

use Exception\TypeException;

class SortedLinkedListTest {
    private function assertTrue(bool $condition, string $message = ''): void {
        if (!$condition) {
            throw new Exception("Assertion failed: " . $message);
        }
    }

    private function assertFalse(bool $condition, string $message = ''): void {
        if ($condition) {
            throw new Exception("Assertion failed: " . $message);
        }
    }

    private function assertEquals(mixed $expected, mixed $actual, string $message = ''): void {
        if ($expected !== $actual) {
            throw new Exception("Assertion failed: Expected $expected, got $actual. " . $message);
        }
    }

    private function assertException(callable $callback, string $expectedException): void {
        try {
            $callback();
            throw new Exception("Expected exception $expectedException was not thrown");
        } catch (\Exception $e) {
            if (!($e instanceof $expectedException)) {
                throw new Exception("Expected exception $expectedException, got " . get_class($e));
            }
        }
    }

    public function runTests(): void {
        $this->testIntegerListHappyPath();
        $this->testStringListHappyPath();
        $this->testEmptyList();
        $this->testTypeSafety();
        $this->testRemoveOperations();
    }

    private function testIntegerListHappyPath(): void {
        echo "\nTesting Integer List Happy Path:\n";
        
        $list = new SortedLinkedList(ListType::INTEGER);
        
        $this->assertTrue($list->isEmpty(), "List should be empty initially");
        $this->assertEquals(0, $list->size(), "Initial size should be 0");
        
        $list->insert(5);
        $list->insert(2);
        $list->insert(8);
        
        $this->assertEquals(3, $list->size(), "Size should be 3 after insertions");
        
        $this->assertTrue($list->contains(5), "List should contain 5");
        $this->assertTrue($list->contains(2), "List should contain 2");
        $this->assertFalse($list->contains(3), "List should not contain 3");
        
        $this->assertEquals(
            "SortedLinkedList([2, 5, 8])",
            (string)$list, 
            "List should be properly sorted",
        );
        
        echo "+ Integer list happy path tests passed\n";
    }

    private function testStringListHappyPath(): void {
        echo "\nTesting String List Happy Path:\n";
        
        $list = new SortedLinkedList(ListType::STRING, ["b", "a", "c"]);
        
        $this->assertFalse($list->isEmpty(), "List should not be empty");
        $this->assertEquals(3, $list->size(), "Initial size should be 3");
        
        $this->assertEquals(
            "SortedLinkedList([a, b, c])", 
            (string)$list, 
            "List should be properly sorted"
        );
        
        $list->insert("d");

        $this->assertEquals(
            "SortedLinkedList([a, b, c, d])", 
            (string)$list,              
            "List should maintain order after insertion",
        );
        
        echo "+ String list happy path tests passed\n";
    }

    private function testEmptyList(): void {
        echo "\nTesting Empty List:\n";
        
        $list = new SortedLinkedList(ListType::INTEGER);
        
        $this->assertTrue($list->isEmpty(), "New list should be empty");
        $this->assertEquals(0, $list->size(), "New list size should be 0");

        $this->assertEquals(
            ListType::INTEGER, 
            $list->getType(), 
            "List type should be INTEGER",
        );
        $this->assertEquals(
            "SortedLinkedList([])", 
            (string)$list, 
            "Empty list string representation",
        );
        
        echo "+ Empty list tests passed\n";
    }

    private function testTypeSafety(): void {
        echo "\nTesting Type Safety:\n";
        
        $intList = new SortedLinkedList(ListType::INTEGER);
        $strList = new SortedLinkedList(ListType::STRING);
        
        $this->assertException(
            fn() => $intList->insert("not an integer"),
            TypeException::class
        );
        
        $this->assertException(
            fn() => $strList->insert(42),
            TypeException::class
        );
        
        $this->assertException(
            fn() => new SortedLinkedList(ListType::INTEGER, ["not an integer"]),
            TypeException::class
        );

        $this->assertException(
            fn() => new SortedLinkedList(ListType::STRING, [1]),
            TypeException::class
        );
        
        echo "+ Type safety tests passed\n";
    }

    private function testRemoveOperations(): void {
        echo "\nTesting Remove Operations:\n";
        
        $list = new SortedLinkedList(ListType::INTEGER, [1, 2, 2, 3, 4]);
        
        $list->removeAll(2);
        $this->assertEquals(
            "SortedLinkedList([1, 3, 4])", 
            (string)$list, 
            "removeAll should remove all occurrences",
        );
        
        $list->removeByIndex(1);
        $this->assertEquals(
            "SortedLinkedList([1, 4])", 
            (string)$list,
            "removeByIndex should remove element at specified index",
        );
        
        $this->assertException(
            fn() => $list->removeByIndex(10),
            \Exception::class
        );
        
        echo "+ Remove operations tests passed\n";
    }
}

// Run all tests
try {
    $test = new SortedLinkedListTest();
    $test->runTests();
    echo "\nAll tests passed successfully!\n";
} catch (Exception $e) {
    echo "\nTest failed: " . $e->getMessage() . "\n";
    exit(1);
} 