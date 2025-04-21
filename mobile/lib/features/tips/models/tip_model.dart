import 'dart:convert';

class TipModel {
  final String content;
  final String type;
  final DateTime? createdAt;

  TipModel({
    DateTime? createdAt,
    required this.content,
    required this.type,
  }): createdAt = createdAt ?? DateTime.now();

  TipModel copyWith({
    String? content,
    String? type,
    DateTime? createdAt,
  }) {
    return TipModel(
      content: content ?? this.content,
      type: type ?? this.type,
      createdAt: createdAt ?? this.createdAt,
    );
  }

  Map<String, dynamic> toMap() {
    return <String, dynamic>{
      'content': content,
      'type': type,
      'createdAt': createdAt?.millisecondsSinceEpoch,
    };
  }

  factory TipModel.fromMap(Map<String, dynamic> map) {
    return TipModel(
      content: map['content'] as String,
      type: map['type'] as String,
      createdAt: map['createdAt'] != null ? DateTime.fromMillisecondsSinceEpoch(map['createdAt'] as int) : null,
    );
  }

  String toJson() => json.encode(toMap());

  factory TipModel.fromJson(String source) => TipModel.fromMap(json.decode(source) as Map<String, dynamic>);

  @override
  String toString() => 'TipModel(content: $content, type: $type, createdAt: $createdAt)';

  @override
  bool operator ==(covariant TipModel other) {
    if (identical(this, other)) return true;
  
    return 
      other.content == content &&
      other.type == type &&
      other.createdAt == createdAt;
  }

  @override
  int get hashCode => content.hashCode ^ type.hashCode ^ createdAt.hashCode;
}
