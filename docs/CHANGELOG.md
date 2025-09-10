## CHANGELOG Format Guidelines

This CHANGELOG file follows the Keep a Changelog format.

### Include the following sections:

* **[Unreleased]**: List changes that have not been released yet.
* **[Version]**: List changes for a specific version, including:
	+ **Added**: New features or functionality.
	+ **Changed**: Changes to existing features or functionality.
	+ **Deprecated**: Features or functionality that are deprecated.
	+ **Removed**: Features or functionality that have been removed.
	+ **Fixed**: Bug fixes.
	+ **Security**: Security-related changes.
* **[Previous Version]**: List changes for previous versions, following the same format as above.

### Use the following format for each section:

* **[Version]**: Use a header with the version number.
* **[Added/Changed/Deprecated/Removed/Fixed/Security]**: Use a subheader with the type of change.
* **[Description]**: Briefly describe the change.
* **[Date]**: Include the date of the change.

># Example:
>
>## [Unreleased]
>
>### Added
>
>* New feature: [brief description]
>* Date: [date]
>
>### Changed
>
>* [brief description]
>* Date: [date]
>
>## [1.0.0]
>
>### Added
>
>* Initial release
>* Date: [date]
>
>### Changed
>
>* [brief description]
>* Date: [date]

## Format the document using Markdown syntax:

* Use # to denote headings (e.g. # CHANGELOG)
* Use ## to denote subheadings (e.g. ## [Unreleased])
* Use ### to denote sub-subheadings (e.g. ### Added)
* Use * to denote bullet points (e.g. * New feature: [brief description])
* Use [text](link) to denote links (e.g. [1.0.0](https://example.com/1.0.0))
* Use [date] to denote dates (e.g. [2022-01-01])
* Use triple backticks () to denote code blocks (e.g. markdown This is a code block
)
* Use blank lines to separate sections and paragraphs

>### Use the following structure:
>```MARKDOWN
> `# CHANGELOG`
> `## [Unreleased]`
> `### Added`
> `### Changed`
> `### Deprecated`
> `### Removed`
> `### Fixed`
> `### Security`
> `## [Version]`
> `### Added`
> `### Changed`
> `### Deprecated`
> `### Removed`
> `### Fixed`
> `### Security`
> `## [Previous Version]`
> `### Added`
> `### Changed`
> `### Deprecated`
> `### Removed`
> `### Fixed`
> `### Security`
>```