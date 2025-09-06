# Contributing to DOKEMA

Terima kasih atas minat Anda untuk berkontribusi pada proyek DOKEMA! Dokumen ini akan membantu Anda memahami cara berkontribusi pada proyek ini.

## Table of Contents

-   [Code of Conduct](#code-of-conduct)
-   [Getting Started](#getting-started)
-   [Development Setup](#development-setup)
-   [How to Contribute](#how-to-contribute)
-   [Code Style Guidelines](#code-style-guidelines)
-   [Testing](#testing)
-   [Pull Request Process](#pull-request-process)
-   [Reporting Issues](#reporting-issues)

## Code of Conduct

Proyek ini mengikuti kode etik yang ramah dan inklusif. Dengan berpartisipasi, Anda diharapkan untuk menjunjung tinggi kode ini.

### Our Standards

-   Menggunakan bahasa yang ramah dan inklusif
-   Menghormati berbagai sudut pandang dan pengalaman
-   Menerima kritik konstruktif dengan baik
-   Fokus pada apa yang terbaik untuk komunitas
-   Menunjukkan empati terhadap anggota komunitas lainnya

## Getting Started

### Prerequisites

Sebelum berkontribusi, pastikan Anda memiliki:

-   PHP >= 8.2
-   Composer
-   Node.js & NPM
-   MariaDB/MySQL
-   Git
-   Text editor atau IDE (VS Code direkomendasikan)

### Development Setup

1. **Fork Repository**

    ```bash
    # Fork repository di GitHub, kemudian clone fork Anda
    git clone https://github.com/YOUR-USERNAME/dokema.git
    cd dokema
    ```

2. **Setup Environment**

    ```bash
    # Install dependencies
    composer install
    npm install

    # Copy environment file
    cp .env.example .env

    # Generate application key
    php artisan key:generate
    ```

3. **Database Setup**

    ```bash
    # Konfigurasi database di .env, kemudian jalankan migration
    php artisan migrate:fresh
    php artisan db:seed
    ```

4. **Build Assets**

    ```bash
    npm run dev
    ```

5. **Start Development Server**
    ```bash
    php artisan serve
    ```

## How to Contribute

### Types of Contributions

Kami menerima berbagai jenis kontribusi:

-   **Bug fixes** - Perbaikan bug yang ada
-   **Feature development** - Pengembangan fitur baru
-   **Documentation** - Perbaikan atau penambahan dokumentasi
-   **Testing** - Penambahan atau perbaikan test cases
-   **Performance improvements** - Optimasi performa
-   **UI/UX improvements** - Perbaikan tampilan dan pengalaman pengguna

### Workflow

1. **Create Issue** (opsional tapi direkomendasikan)

    - Buat issue untuk diskusi fitur atau bug
    - Tunggu feedback dari maintainer

2. **Create Branch**

    ```bash
    git checkout -b feature/your-feature-name
    # atau
    git checkout -b fix/bug-description
    ```

3. **Make Changes**

    - Ikuti [Code Style Guidelines](#code-style-guidelines)
    - Buat commit yang descriptive
    - Test perubahan Anda

4. **Push & Create PR**
    ```bash
    git push origin your-branch-name
    ```
    - Buat Pull Request di GitHub
    - Isi template PR dengan lengkap

## Code Style Guidelines

### PHP Standards

-   Ikuti [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standard
-   Gunakan type hints untuk parameter dan return values
-   Gunakan nama variabel dan method yang descriptive
-   Tulis docblocks untuk methods dan classes

```php
/**
 * Process internship application workflow
 *
 * @param DataMagang $application
 * @param string $action
 * @return bool
 */
public function processApplication(DataMagang $application, string $action): bool
{
    // Implementation here
}
```

### Blade Templates

-   Gunakan consistent indentation (4 spaces)
-   Pisahkan logic dari presentation
-   Gunakan Blade components untuk reusability
-   Ikuti naming convention untuk components

```blade
{{-- Good --}}
<x-admin.form-input
    name="nama"
    label="Nama Lengkap"
    :value="old('nama')"
    required />

{{-- Bad --}}
<input type="text" name="nama" value="{{ old('nama') }}" class="form-control">
```

### JavaScript/Alpine.js

-   Gunakan camelCase untuk variabel dan fungsi
-   Dokumentasikan complex logic
-   Minimal DOM manipulation
-   Gunakan Alpine.js directives dengan bijak

```javascript
// Good
Alpine.data("formHandler", () => ({
    isLoading: false,

    async submitForm() {
        this.isLoading = true;
        // Implementation
    },
}));
```

### CSS/Tailwind

-   Gunakan Tailwind utility classes
-   Hindari custom CSS kecuali absolutely necessary
-   Gunakan responsive design principles
-   Maintain consistency dengan design system

## Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/UserTest.php

# Run with coverage
php artisan test --coverage
```

### Writing Tests

-   Tulis tests untuk setiap fitur baru
-   Gunakan descriptive test names
-   Test both happy path dan edge cases
-   Mock external dependencies

```php
public function test_user_can_create_internship_application(): void
{
    $user = User::factory()->create(['role' => 'magang']);

    $response = $this->actingAs($user)->post('/magang', [
        'nama' => 'John Doe',
        'departemen' => 'IT',
        // ... other data
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('data_magang', ['nama' => 'John Doe']);
}
```

## Pull Request Process

### Before Submitting

-   [ ] Code follows style guidelines
-   [ ] Tests pass locally
-   [ ] New features have tests
-   [ ] Documentation updated if needed
-   [ ] No console errors or warnings
-   [ ] Database migrations are reversible

### PR Template

Gunakan template berikut untuk PR Anda:

```markdown
## Description

Brief description of changes

## Type of Change

-   [ ] Bug fix
-   [ ] New feature
-   [ ] Documentation update
-   [ ] Performance improvement
-   [ ] Other (specify)

## Testing

-   [ ] Tests pass locally
-   [ ] Added new tests for new functionality
-   [ ] Manual testing completed

## Screenshots (if applicable)

## Checklist

-   [ ] Code follows style guidelines
-   [ ] Self-review completed
-   [ ] Documentation updated
-   [ ] No breaking changes
```

### Review Process

1. Maintainer akan review PR Anda
2. Jika ada feedback, lakukan perubahan yang diperlukan
3. Setelah approved, PR akan di-merge
4. Branch akan dihapus setelah merge

## Reporting Issues

### Bug Reports

Gunakan template berikut untuk bug reports:

```markdown
**Bug Description**
A clear description of the bug

**Steps to Reproduce**

1. Go to '...'
2. Click on '....'
3. Scroll down to '....'
4. See error

**Expected Behavior**
What you expected to happen

**Screenshots**
If applicable, add screenshots

**Environment:**

-   OS: [e.g. Windows 11]
-   Browser: [e.g. Chrome 91]
-   PHP Version: [e.g. 8.2]
-   Laravel Version: [e.g. 11.0]
```

### Feature Requests

```markdown
**Feature Description**
Clear description of the feature

**Problem/Use Case**
What problem does this solve?

**Proposed Solution**
How you envision this working

**Alternatives Considered**
Other solutions you considered

**Additional Context**
Any other context or screenshots
```

## Getting Help

Jika Anda membutuhkan bantuan:

-   📧 Email: [support@sukmaajidigital.com](mailto:support@sukmaajidigital.com)
-   🐛 Issues: [GitHub Issues](https://github.com/sukmaajidigital/dokema/issues)
-   💬 Discussions: [GitHub Discussions](https://github.com/sukmaajidigital/dokema/discussions)

## Recognition

Kontributor akan diakui melalui:

-   Git commit history
-   Contributors section di README
-   Release notes untuk kontribusi besar
-   GitHub contributor graphs

---

Terima kasih telah berkontribusi pada DOKEMA! 🚀
