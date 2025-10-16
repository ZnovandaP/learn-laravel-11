<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Post;
use App\Models\TestCategory;
use App\Models\User;
use App\Models\Voucher;
use Database\Seeders\CommentSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\HistorySeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\TagSeeder;
use Database\Seeders\VirtualAccountSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EloquentLearningTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    // Setup code here, if needed
    DB::delete('delete from vouchers');
    DB::delete('delete from test_categories');
    DB::delete('delete from customers');
    DB::delete('delete from comments');
    DB::delete('delete from user_like_posts');
    DB::delete('delete from user_like_posts');
    DB::delete('delete from images');
    DB::delete('delete from histories');
    DB::delete('delete from taggables');
    DB::delete('delete from tags');
  }

  public function testInsertDataWithUuid()
  {
    $voucher = new Voucher();
    $voucher->name = 'Sample Diskon 10%';
    $voucher->save();

    self::assertNotNull($voucher->id);
    self::assertIsString($voucher->id);
  }

  public function testSoftDeleteVoucherModel()
  {
    $voucher = new Voucher();
    $voucher->name = 'Sample Diskon 10%';
    $voucher->save();

    $voucherId = $voucher->id;
    self::assertNotNull($voucherId);

    // delete
    $voucher->delete();

    // check data still in database
    $checkVoucher = Voucher::withTrashed()->find($voucherId);
    self::assertNotNull($checkVoucher);
    self::assertNotNull($checkVoucher->deleted_at);

    // check data not found
    $checkVoucher = Voucher::find($voucherId);
    self::assertNull($checkVoucher);

    // restore
    $voucher->restore();
    $checkVoucher = Voucher::find($voucherId);
    self::assertNotNull($checkVoucher);
  }

  public function testQueryWithGlobalScope()
  {
    TestCategory::insert(
      [
        [
          'id' => 'FOOD',
          'name' => 'Nugget',
          'description' => 'Test Description',
          'is_active' => true,
        ],
        [
          'id' => 'GADGET',
          'name' => 'Gadget Mobile',
          'description' => 'Test Description',
          'is_active' => false,
        ],
      ]
    );

    $categories = TestCategory::all();
    $this->assertCount(1, $categories);
    $this->assertEquals('Nugget', $categories[0]->name);
  }

  public function testOneToOneRelation()
  {
    $this->seed([CustomerSeeder::class, WalletSeeder::class]);

    $customer = Customer::where('email', 'johndoe@example.com')->first();
    $this->assertNotNull($customer);

    $wallet = $customer->wallet;
    $this->assertNotNull($wallet);

    $customer = $wallet->customer;
    $this->assertNotNull($customer);

    $this->assertEquals('John Doe', $customer->name);
    $this->assertEquals('johndoe@example.com', $customer->email);
    $this->assertEquals($customer->id, $wallet->customer_id);
    $this->assertEquals(1000, $wallet->balance);
  }

  public function testHasOneOfManyRelation()
  {
    $category = Category::first();
    $this->assertNotNull($category);

    $newestPost = $category->newestPost;
    $this->assertNotNull($newestPost);
  }

  public function testOneToOneThroughRelation()
  {
    $this->seed([CustomerSeeder::class, WalletSeeder::class, VirtualAccountSeeder::class]);

    $customer = Customer::where('email', 'johndoe@example.com')->first();
    $this->assertNotNull($customer);

    $virtualAccount = $customer->virtualAccount;
    $this->assertNotNull($virtualAccount);
    $this->assertEquals('BCA', $virtualAccount->bank_name);
    $this->assertEquals('1234567890', $virtualAccount->va_number);
  }

  public function testHasManyThroughRelation()
  {
    $this->seed([CommentSeeder::class]);

    $post = Post::first();
    $this->assertNotNull($post);

    $category = Category::where('id', $post->category_id)->first();
    $this->assertNotNull($category);

    $comments = $category->comments;
    $this->assertNotNull($comments);
    $this->assertCount(2, $comments);

  }

  public function testManyToManyRelation()
  {
    $user = User::first();
    $this->assertNotNull($user);

    // create pivot data == user like 2 post id 1 and 2
    $user->likePosts()->attach([1, 2]);

    $post = Post::find(1);
    $this->assertNotNull($post);
    $this->assertCount(1, $post->likedByUsers);

    $posts = $user->likePosts;
    $this->assertCount(2, $posts);
    foreach ($posts as $post) {
      $this->assertNotNull($post->pivot->created_at);
      $this->assertNotNull($post->pivot->updated_at);
      $this->assertEquals($user->id, $post->pivot->user_id);
      $this->assertEquals($post->id, $post->pivot->post_id);
    }

    // delete pivot data
    $user->likePosts()->detach([1]);
    // refresh relation or caching from eloquent
    $user->load('likePosts');
    $this->assertCount(1, $user->likePosts);
  }

  public function testOnetoOnePolymorphic()
  {
    $this->seed(ImageSeeder::class);

    $user = User::first();
    $this->assertNotNull($user);

    $this->assertNotNull($user->image);
    $this->assertEquals('https://avatar.vercel.sh/u/123', $user->image->url);
    $this->assertEquals($user->id, $user->image->imageable_id);
    $this->assertEquals(User::class, $user->image->imageable_type);

    $post = Post::first();
    $this->assertNotNull($post);

    $this->assertNotNull($post->image);
    $this->assertEquals('https://post-image.vercel.sh/p/123', $post->image->url);
    $this->assertEquals($post->id, $post->image->imageable_id);
    $this->assertEquals(Post::class, $post->image->imageable_type);

    // reverse
    $image = $user->image;
    $this->assertNotNull($image);
    $this->assertEquals($user->id, $image->imageable->id);
    $this->assertEquals($user->name, $image->imageable->name);
    $this->assertEquals($user->username, $image->imageable->username);

    $image = $post->image;
    $this->assertNotNull($image);
    $this->assertEquals($post->id, $image->imageable->id);
    $this->assertEquals($post->title, $image->imageable->title);
    $this->assertEquals($post->slug, $image->imageable->slug);
  }

  public function testOneToManyPolymorphic()
  {
    $this->seed([HistorySeeder::class]);

    $category = Category::first();
    $this->assertNotNull($category);
    $this->assertCount(2, $category->histories);

    $post = Post::first();
    $this->assertNotNull($post);
    $this->assertCount(2, $post->histories);

    // reverse
    $history = $category->histories[0];
    $this->assertNotNull($history);
    $this->assertEquals($category->id, $history->historable_id);
    $this->assertEquals(Category::class, $history->historable_type);
  }

  public function testManyToManyPolymorphic()
  {
    $this->seed([TagSeeder::class]);

    $post = Post::first();
    $this->assertNotNull($post);
    $this->assertCount(3, $post->tags);

    $category = Category::first();
    $this->assertNotNull($category);
    $this->assertCount(1, $category->tags);

    // reverse
    $tag = $post->tags[0];
    $this->assertNotNull($tag);
    $this->assertEquals('Laravel', $tag->name);

    $this->assertCount(1, $tag->posts);
    $this->assertEquals($post->id, $tag->posts[0]->id);

    $this->assertCount(1, $tag->categories);
    $this->assertEquals($category->id, $tag->categories[0]->id);

    dd($tag->posts->toArray(), $tag->categories->toArray());
  }
}


