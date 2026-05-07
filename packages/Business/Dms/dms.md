# 📄 DMS Full Package (Models + Migrations + Service + Workflow + Filament)

---

# 1. 📦 Models

## Document

```php
class Document extends Model
{
    protected $fillable = [
        'title',
        'code',
        'owner_id',
        'owner_type',
        'status'
    ];

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class);
    }
}
```

## DocumentVersion

```php
class DocumentVersion extends Model
{
    protected $fillable = [
        'document_id',
        'version',
        'status',
        'effective_date'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function content()
    {
        return $this->hasOne(DocumentContent::class);
    }
}
```

## DocumentContent

```php
class DocumentContent extends Model
{
    protected $fillable = [
        'document_version_id',
        'type',
        'path',
        'external_url'
    ];
}
```

---

# 2. 🧱 Migrations (Company DB)

```php
Schema::create('documents', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('code')->unique();
    $table->morphs('owner');
    $table->string('status')->default('draft');
    $table->timestamps();
});

Schema::create('document_versions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('document_id');
    $table->string('version');
    $table->string('status');
    $table->timestamp('effective_date')->nullable();
    $table->timestamps();
});

Schema::create('document_contents', function (Blueprint $table) {
    $table->id();
    $table->foreignId('document_version_id');
    $table->string('type');
    $table->string('path')->nullable();
    $table->string('external_url')->nullable();
});
```

---

# 3. ⚙️ DMS Service (Core Logic)

```php
class DocumentService implements DocumentServiceInterface
{
    public function createDocument(array $data): Document
    {
        return Document::create($data);
    }

    public function createVersion(Document $document, array $data): DocumentVersion
    {
        return $document->versions()->create([
            ...$data,
            'status' => 'draft'
        ]);
    }

    public function submitForApproval(DocumentVersion $version): void
    {
        $version->update(['status' => 'pending']);

        app(StartWorkflowAction::class)
            ->execute('dms.approval', $version);
    }

    public function publish(DocumentVersion $version): void
    {
        if ($version->status !== 'approved') {
            throw new \Exception('Not approved');
        }

        $version->update([
            'status' => 'published',
            'effective_date' => now()
        ]);
    }
}
```

---

# 4. 🔄 Workflow Integration

## Workflow Key

```php
'dms.approval'
```

## Flow

1. Submit
2. Manager Approval
3. QA Approval
4. Final Approval

## Trigger

```php
StartWorkflowAction::execute('dms.approval', $documentVersion);
```

---

# 5. 🎨 Filament Resources

## DocumentResource

```php
class DocumentResource extends Resource
{
    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')->required(),
            TextInput::make('code')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title'),
            TextColumn::make('code'),
            TextColumn::make('status'),
        ]);
    }
}
```

---

## DocumentVersionResource

```php
class DocumentVersionResource extends Resource
{
    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('version'),
            TextColumn::make('status'),
        ]);
    }
}
```

---

# 6. 🔐 Policies (Example)

```php
class DocumentPolicy
{
    public function view(User $user, Document $doc)
    {
        return true;
    }

    public function update(User $user, Document $doc)
    {
        return $doc->status === 'draft';
    }
}
```

---

# 7. 🚀 Summary Flow

```text
Create Document
→ Create Version
→ Upload Content
→ Submit
→ Workflow Approval
→ Publish
```

---

# END

---

# 📄 DMS ISO v2 (Enterprise-Grade Document Management System)

## 🧠 Principles

* Controlled distribution
* Mandatory acknowledgement
* Obsolete control
* Review cycles
* Full audit compliance

Lifecycle:
Draft → Review → Approved → Published → Obsolete → Archived

---

## 🧱 New Tables

### document_distributions

* document_version_id
* target_id
* target_type

### document_acknowledgements

* document_version_id
* user_id
* acknowledged_at

### document_reviews

* document_version_id
* due_date
* completed_at

---

## ⚙️ New Service Methods

* distribute()
* acknowledge()
* markObsolete()
* scheduleReview()

---

## 🔄 Workflow

dms.approval:

* Author
* Manager
* QA
* Final

---

## 🔔 Events

* DocumentPublished
* AcknowledgementPending
* ReviewDue

---

## 🧾 Audit

Log everything:

* create
* submit
* approve
* publish
* acknowledge
* obsolete

---

## 🎨 UI

* Register
* Distribution Matrix
* Acknowledgement Dashboard
* Review Calendar

---

## 📊 Compliance

* % acknowledged
* overdue
* review due

---

## ✅ Rules

* Single active version
* Mandatory acknowledgement
* Controlled distribution
* Review enforcement