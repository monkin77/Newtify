$('#tags').select2({
    multiple: true,
    maximumSelectionLength: 3,
    tokenSeparators: [',', ' ', ";"],
    theme: "classic",
});

$('#favoriteTags').select2({
    multiple: true,
    tokenSeparators: [',', ' ', ";"],
    theme: "classic",
});
