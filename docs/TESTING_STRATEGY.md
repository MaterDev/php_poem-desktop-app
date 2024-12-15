# Poetry Desktop App - Testing Strategy

## Testing Overview

### Testing Pyramid
1. Unit Tests (60%)
2. Integration Tests (25%)
3. End-to-End Tests (15%)

## Unit Testing

### Backend Tests

#### Controller Tests
```php
class PoemControllerTest extends TestCase
{
    public function test_can_create_poem()
    {
        $response = $this->postJson('/api/poems', [
            'title' => 'Test Poem',
            'content' => 'Test content'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'title',
                        'content'
                    ]
                ]);
    }
}
```

#### Service Tests
```php
class PoemServiceTest extends TestCase
{
    public function test_updates_poem_position()
    {
        $service = new PoemService();
        $poem = Poem::factory()->create();

        $result = $service->updatePosition($poem, 100, 200);

        $this->assertEquals(100, $result->position_x);
        $this->assertEquals(200, $result->position_y);
    }
}
```

### Frontend Tests

#### Component Tests
```javascript
describe('Window Component', () => {
    it('should be draggable', () => {
        const wrapper = mount(Window);
        
        wrapper.trigger('mousedown');
        wrapper.trigger('mousemove', { clientX: 100, clientY: 100 });
        wrapper.trigger('mouseup');

        expect(wrapper.emitted('position-changed')).toBeTruthy();
    });
});
```

#### Utility Tests
```javascript
describe('Position Calculator', () => {
    it('should calculate grid position', () => {
        const result = calculateGridPosition(105, 205);
        
        expect(result.x).toBe(100);
        expect(result.y).toBe(200);
    });
});
```

## Integration Testing

### API Integration Tests
```php
class PoemApiTest extends TestCase
{
    public function test_full_poem_lifecycle()
    {
        // Create poem
        $response = $this->postJson('/api/poems', [
            'title' => 'Test Poem',
            'content' => 'Content'
        ]);
        $poemId = $response->json('data.id');

        // Update position
        $this->patchJson("/api/poems/{$poemId}/position", [
            'position_x' => 100,
            'position_y' => 200
        ])->assertStatus(200);

        // Delete poem
        $this->deleteJson("/api/poems/{$poemId}")
             ->assertStatus(204);
    }
}
```

### Frontend Integration Tests
```javascript
describe('Desktop Environment', () => {
    it('should handle window and icon interactions', () => {
        const desktop = mount(Desktop);
        const icon = desktop.find('.desktop-icon');
        const window = desktop.find('.window');

        // Test icon click opens window
        icon.trigger('click');
        expect(window.isVisible()).toBe(true);

        // Test window drag
        window.trigger('mousedown');
        window.trigger('mousemove', { clientX: 100, clientY: 100 });
        expect(window.props('position')).toEqual({ x: 100, y: 100 });
    });
});
```

## End-to-End Testing

### Cypress Tests
```javascript
describe('Poetry Desktop App', () => {
    it('should create and manage poems', () => {
        cy.visit('/');
        
        // Create new poem
        cy.get('[data-test="new-poem"]').click();
        cy.get('[data-test="poem-title"]').type('My Poem');
        cy.get('[data-test="poem-content"]').type('Content');
        cy.get('[data-test="save-poem"]').click();

        // Verify poem icon appears
        cy.get('[data-test="poem-icon"]')
          .should('be.visible')
          .and('contain', 'My Poem');

        // Open poem window
        cy.get('[data-test="poem-icon"]').dblclick();
        cy.get('[data-test="poem-window"]')
          .should('be.visible');
    });
});
```

## Performance Testing

### Load Testing
```javascript
import { check } from 'k6';
import http from 'k6/http';

export default function() {
    const res = http.get('http://localhost:8000/api/poems');
    check(res, {
        'status is 200': (r) => r.status === 200,
        'response time < 200ms': (r) => r.timings.duration < 200
    });
}
```

### Frontend Performance
```javascript
describe('Performance Metrics', () => {
    it('should render efficiently', () => {
        performance.mark('start');
        
        mount(Desktop);
        
        performance.mark('end');
        performance.measure('desktop-render', 'start', 'end');
        
        const measure = performance.getEntriesByName('desktop-render')[0];
        expect(measure.duration).toBeLessThan(100);
    });
});
```

## Security Testing

### CSRF Protection
```php
class SecurityTest extends TestCase
{
    public function test_requires_csrf_token()
    {
        $response = $this->postJson('/api/poems', [
            'title' => 'Test'
        ]);

        $response->assertStatus(419);
    }
}
```

### Input Validation
```php
class ValidationTest extends TestCase
{
    public function test_sanitizes_input()
    {
        $response = $this->postJson('/api/poems', [
            'title' => '<script>alert("xss")</script>'
        ]);

        $poem = Poem::first();
        $this->assertStringNotContainsString(
            '<script>', 
            $poem->title
        );
    }
}
```

## Test Data Management

### Factories
```php
class PoemFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraphs(3, true),
            'position_x' => $this->faker->numberBetween(0, 1000),
            'position_y' => $this->faker->numberBetween(0, 1000)
        ];
    }
}
```

### Seeders
```php
class TestingSeeder extends Seeder
{
    public function run()
    {
        Poem::factory()
            ->count(10)
            ->create();
    }
}
```

## Continuous Integration

### GitHub Actions
```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
    
    - name: Run Tests
      run: |
        composer install
        php artisan test
```

## Test Documentation

### Test Case Template
```markdown
## Test Case: [Feature Name]

### Objective
[Description of what is being tested]

### Prerequisites
- [Required setup]
- [Required data]

### Steps
1. [Step 1]
2. [Step 2]
3. [Step 3]

### Expected Results
- [Expected outcome 1]
- [Expected outcome 2]

### Actual Results
- [Actual outcome 1]
- [Actual outcome 2]

### Pass/Fail Criteria
- [ ] Criterion 1
- [ ] Criterion 2
```
