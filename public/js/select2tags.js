$('#tags').select2({
    multiple: true,
    maximumSelectionLength: 3,
    tokenSeparators: [',', ' ', ";"],
    theme: "bootstrap-5",
});

$('#filterTags').select2({
    multiple: true,
    width: '15%',
    tokenSeparators: [',', ' ', ";"],
    theme: "bootstrap-5",
});

$('#favoriteTags').select2({
    multiple: true,
    tokenSeparators: [',', ' ', ";"],
    theme: "classic",
});
