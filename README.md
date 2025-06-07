# Sorted Linked List

A PHP implementation of a sorted linked list that maintains elements in sorted order. Supports both string and integer values.

## How to Run

1. Make sure you have PHP 8.0 or higher installed
2. Include the required files:
```php
require_once 'SortedLinkedList.php';
require_once 'ListType.php';
require_once 'Exception/TypeException.php';
```

## Usage

```php
use Exception\TypeException;

// Create a list of integers
$list = new SortedLinkedList(ListType::INTEGER);

// Insert elements (automatically sorted)
$list->insert(3);
$list->insert(1);
$list->insert(4);

// Remove elements
$list->removeAll(1);  // Remove all occurrences of 1
$list->removeByIndex(0);  // Remove first element

// Check if element exists
$exists = $list->contains(4);

// Get list size
$size = $list->size();

// Iterate through the list
$list->rewind();
while ($list->currentPosition() < $list->size()) {
    $value = $list->currentValue();
    echo $value . "\n";
    $list->next();
}
```

To run the test file:
```bash
php test_sorted_linked_list.php
```
