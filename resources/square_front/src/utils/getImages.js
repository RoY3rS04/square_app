export function getLastUserImage(user) {
    const images = user.media.filter((image) => {
        if(image.collection_name === 'user_images') {
            return image;
        }
    })

    return images[images.length - 1].original_url;
}

export function getLastBackgroundImage(user) {
    const images = user.media.filter((image) => {
        if(image.collection_name === 'user_backgrounds') {
            return image;
        }
    })

    return images[images.length - 1].original_url;
}
