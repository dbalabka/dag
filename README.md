This experimental library helps to organize tasks and its dependencies. 
Amphp framework support provide ability to execute tasks asynchronously.

Following example show basic usage:
```php
$taskDag = (new TaskGraph())
    
    // root task in graph (there might be multiple root tasks)
    ->addTask(new ClosureTask('1', function () {
        echo $this->name;
    }))
    
    // these two tasks were be executed only after task with name '1'
    // the order of execution is not guaranteed
    ->addTask(new ClosureTask('2', function () {
        echo 2;
    }, ['1'])) 
    ->addTask(new ClosureTask('3', function () {
        echo 3;
    }, ['1']))

    // this is the last task in graph (there might be multiple leaf tasks)
    ->addTask(new ClosureTask('4', function () {
        echo 4;
    }, ['3', '2']));

Amp\Loop::run($taskDag); // will output "1234"
```

The visualization of the execution graph will be following:
```text
     +------+
   +-+Task 1+-+
   | +------+ |
   |          |
+--v---+  +---v--+
|Task 2|  |Task 3|
+--+---+  +---+--+
   |          |
   | +------+ |
   +->Task 4<-+
     +------+

```
  
