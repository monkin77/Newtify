const saveFavoriteTags = () => {
    const tagIds = [];

    const tagsArray = selectAll('.tagContainer');
    tagsArray.forEach(tagElem => {
        console.log(tagElem);
        if (tagElem.classList.contains('selectedTag')) tagIds.push(tagElem.id);
    });

    console.log(tagIds);
}