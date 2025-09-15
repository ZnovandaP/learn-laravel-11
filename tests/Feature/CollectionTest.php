<?php

namespace Tests\Feature;

use App\Demo\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use PHPUnit\Event\Code\TestCollection;
use Tests\TestCase;

class CollectionTest extends TestCase
{
  private array $base = [1, 2, 3, 4, 5];

  /**
   * A basic feature test example.
   */
  public function testMakeCollection(): void
  {
    $collection = collect($this->base);
    $this->assertInstanceOf(Collection::class, $collection);
    $this->assertEqualsCanonicalizing($this->base, $collection->all());
  }

  public function testCollectionManipulationData()
  {
    $data = collect([]);
    $data->push(1, 2, 3);
    $this->assertEqualsCanonicalizing([1, 2, 3], $data->all());

    $data->prepend(0);
    $this->assertEqualsCanonicalizing([0, 1, 2, 3], $data->all());

    $popData = $data->pop();
    $this->assertEquals(3, $popData);
    $this->assertEqualsCanonicalizing([0, 1, 2], $data->all());
  }

  public function testMapCollection()
  {
    // common map
    $data = collect($this->base);
    $mapped = $data->map(fn($item) => $item * 2);
    $this->assertEqualsCanonicalizing([2, 4, 6, 8, 10], $mapped->all());

    // test MapInto
    $dataY = collect(['zidane', 'novanda']);
    $dataPerson = $dataY->mapInto(Person::class);
    $this->assertEquals('zidane', $dataPerson->first()->firstname);
    $this->assertEquals('novanda', $dataPerson->last()->firstname);
    $this->assertEqualsCanonicalizing([
      new Person('zidane', 0),
      new Person('novanda', 1),
    ], $dataPerson->all());


    // test mapSpread
    $dataX = collect([['zidane', 'putra'], ['novanda', 'putra']]);
    $dataPersonX = $dataX->mapSpread(fn($firstname, $lastname) => new Person($firstname, $lastname));
    $this->assertEquals('zidane', $dataPersonX->first()->firstname);
    $this->assertEquals('putra', $dataPersonX->first()->lastname);
    $this->assertEquals('novanda', $dataPersonX->last()->firstname);
    $this->assertEquals('putra', $dataPersonX->last()->lastname);
    $this->assertEqualsCanonicalizing([
      new Person('zidane', 'putra'),
      new Person('novanda', 'putra'),
    ], $dataPersonX->all());

    // test mapToGroups
    $dataZ = collect([
      ['name' => 'zidane', 'team' => 'A'],
      ['name' => 'novanda', 'team' => 'A'],
      ['name' => 'putra', 'team' => 'B'],
      ['name' => 'eko', 'team' => 'B'],
      ['name' => 'radin', 'team' => 'B'],
      ['name' => 'zakia', 'team' => 'A'],
      ['name' => 'sahroni', 'team' => 'C'],
    ]);
    $grouped = $dataZ->mapToGroups(fn($item) => [$item['team'] => $item['name']]);
    $this->assertEqualsCanonicalizing([
      'A' => collect(['zidane', 'novanda', 'zakia']),
      'B' => collect(['putra', 'eko', 'radin']),
      'C' => collect(['sahroni']),
    ], $grouped->all());
  }

  public function testZipCollection()
  {
    $data1 = collect(['zidane', 'novanda', 'putra']);
    $data2 = collect([1, 2, 3]);
    $zipped = $data1->zip($data2);
    $this->assertEqualsCanonicalizing([
      collect(['zidane', 1]),
      collect(['novanda', 2]),
      collect(['putra', 3]),
    ], $zipped->all());
  }

  public function testConcatCollection()
  {
    $data = collect($this->base);
    $data = $data->concat([6, 7, 8]);
    $this->assertEqualsCanonicalizing([1, 2, 3, 4, 5, 6, 7, 8], $data->all());
  }

  public function testCombineCollection()
  {
    $keys = collect(['name', 'age', 'address']);
    $values = collect(['zidane', 21, 'cimahi']);
    $combined = $keys->combine($values);
    $this->assertEqualsCanonicalizing([
      'name' => 'zidane',
      'age' => 21,
      'address' => 'cimahi',
    ], $combined->all());
  }

  public function testCollapseCollection()
  {
    $data = collect([
      [1, 2, 3],
      [4, 5, 6],
      [7, 8, 9],
    ]);
    $collapsed = $data->collapse();
    $this->assertEqualsCanonicalizing([1, 2, 3, 4, 5, 6, 7, 8, 9], $collapsed->all());
  }

  public function testFlatMap()
  {
    $data = [
      [
        'name' => 'Zidane',
        'hobby' => ['basket', 'futsal']
      ],
      [
        'name' => 'Amba',
        'hobby' => ['basket', 'futsal', 'kokang linggis']
      ],
      [
        'name' => 'Rusdi',
        'hobby' => ['basket', 'kokang linggis']
      ],
    ];

    $collection = collect($data);
    $hobbies = $collection->flatMap(fn($item) => $item['hobby']);
    $this->assertEqualsCanonicalizing(['basket', 'futsal', 'kokang linggis'], $hobbies->unique()->values()->all());
  }

  public function testFlatten()
  {
    $data = [[[1, 2, 3, [4, 5]]], [[[[[[[[[[6, 7]]]]]]]]]]];
    $collection = collect($data);
    $flattened = $collection->flatten(INF); // INF = infinite depth
    $this->assertEqualsCanonicalizing([1, 2, 3, 4, 5, 6, 7], $flattened->all());
  }

  public function testStringRepresentation()
  {
    $data = collect(['zidane', 'novanda', 'putra']);
    $this->assertEquals('zidane-novanda-putra', $data->join('-'));
    $this->assertEquals('zidane-novanda_putra', $data->join('-', '_'));
  }

  public function testFilter()
  {
    $dataValueStudent = [
      [
        'name' => 'Zidane',
        'score' => 90
      ],
      [
        'name' => 'Rusdi',
        'score' => 70
      ],
      [
        'name' => 'Ambatukam',
        'score' => 85
      ],
    ];

    $studentCollection = collect($dataValueStudent);
    $studentFilter = $studentCollection->filter(fn($item) => $item['score'] >= 80);
    $this->assertEquals([
      [
        'name' => 'Zidane',
        'score' => 90
      ],
      [
        'name' => 'Ambatukam',
        'score' => 85
      ],
    ], $studentFilter->values()->all());

    $dataValue = collect([50, 60, 70, 80, 90, 100]);
    $dataFilter = $dataValue->filter(fn($item) => $item > 70);
    $this->assertEquals([80, 90, 100], $dataFilter->values()->all()); // values() reset index key
  }

  public function testPartition()
  {
    $dataValue = collect([50, 60, 70, 80, 90, 100]);
    [$pass, $fail] = $dataValue->partition(fn($item) => $item > 70);
    $this->assertEquals([80, 90, 100], $pass->values()->all());
    $this->assertEquals([50, 60, 70], $fail->values()->all());
  }

  public function testCollectionTesting()
  {
    $dataStudentArr = ['Zidane', 'Rusdi', 'Ambatukam', 'Sahroni', 'Ci Imut'];
    $dataStudent = collect($dataStudentArr);
    $this->assertTrue($dataStudent->contains('Rusdi'));
    $this->assertFalse($dataStudent->contains(fn($item) => $item === 'Eko'));
    $this->assertFalse($dataStudent->contains('name', 'Eko'));
    $this->assertFalse($dataStudent->has('Eko'));
    $this->assertTrue($dataStudent->has([0, 1, 2, 3, 4]));
    $this->assertFalse($dataStudent->has([0, 1, 2, 3, 4, 5]));
    $this->assertTrue($dataStudent->hasAny([0, 1, 2, 3, 4, 5]));
  }

  public function testCollectionGrouping()
  {
    $dataValue = collect([50, 60, 70, 80, 90, 100]);
    $group = $dataValue->groupBy(fn($item) => $item > 70);

    $this->assertEquals([
      true => collect([80, 90, 100]),
      false => collect([50, 60, 70])
    ], $group->all());
  }

  public function testSliceCollection()
  {
    $dataValue = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
    $slice = $dataValue->slice(2, 3); // start from index 2, take 3 items
    $this->assertEquals([3, 4, 5], $slice->values()->all());

    // take/skip collection
    $take = $dataValue->take(3);
    $this->assertEquals([1, 2, 3], $take->values()->all());

    $take = $dataValue->take(-3);
    $this->assertEquals([8, 9, 10], $take->values()->all());

    $take = $dataValue->takeUntil(fn($item) => $item == 7);
    $this->assertEquals([1, 2, 3, 4, 5, 6], $take->values()->all());

    $take = $dataValue->takeWhile(fn($item) => $item < 7);
    $this->assertEquals([1, 2, 3, 4, 5, 6], $take->values()->all());

    $skip = $dataValue->skip(7);
    $this->assertEquals([8, 9, 10], $skip->values()->all());

    $skip = $dataValue->skip(-3);
    $this->assertEquals([8, 9, 10], $skip->values()->all());

    $skip = $dataValue->skipUntil(fn($item) => $item == 7);
    $this->assertEquals([7, 8, 9, 10], $skip->values()->all());

    $skip = $dataValue->skipWhile(fn($item) => $item < 7);
    $this->assertEquals([7, 8, 9, 10], $skip->values()->all());
  }

  public function testChunkCollection()
  {
    $dataValue = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
    $chunk = $dataValue->chunk(3);
    $this->assertEquals([1, 2, 3], $chunk->first()->values()->all());
    $this->assertEquals([4, 5, 6], $chunk->get(1)->values()->all());
    $this->assertEquals([7, 8, 9], $chunk->get(2)->values()->all());
    $this->assertEquals([10], $chunk->last()->values()->all());
  }

  public function testFirstLastCollection()
  {
    $dataValue = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
    $this->assertEquals(1, $dataValue->first());
    $this->assertEquals(10, $dataValue->last());

    $this->assertEquals(6, $dataValue->first(fn($item) => $item > 5));
    $this->assertEquals(5, $dataValue->last(fn($item) => $item < 6));
  }

  public function testRandomCollection()
  {
    $dataValue = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
    $random = $dataValue->random();
    $this->assertTrue($dataValue->contains($random));

    $randoms = $dataValue->random(3);
    $this->assertCount(3, $randoms);
    foreach ($randoms as $random) {
      $this->assertTrue($dataValue->contains($random));
    }
  }

  public function testLazyCollection()
  {
    $collection = LazyCollection::make(function () {
      // generator function
      $value = 0;
      while ($value < 1000000) {
        yield $value++;
      }
    });

    $this->assertEquals([0, 1, 2, 3, 4, 5, 6, 7, 8, 9], $collection->take(10)->all());
  }
}
