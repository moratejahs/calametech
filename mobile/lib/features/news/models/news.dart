class News {
  final String imagePath;
  final String title;
  final String description;
  final String? url;

  News({
    required this.imagePath,
    required this.title,
    required this.description,
    this.url,
  });

  factory News.fromJson(Map<String, dynamic> json) {
    return News(
      imagePath: json['image_path'],
      title: json['title'],
      description: json['description'],
      url: json['url'],
    );
  }
}
