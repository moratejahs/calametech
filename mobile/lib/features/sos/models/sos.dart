import 'dart:convert';

class SOS {
  final int? id;
  final double lat;
  final double long;
  final String status;

  SOS({
    this.id,
    required this.lat,
    required this.long,
    this.status = 'pending',
  });

  SOS copyWith({
    int? id,
    double? lat,
    double? long,
    String? status,
  }) {
    return SOS(
      id: id ?? this.id,
      lat: lat ?? this.lat,
      long: long ?? this.long,
      status: status ?? this.status,
    );
  }

  Map<String, dynamic> toMap() {
    return <String, dynamic>{
      'id': id,
      'lat': lat,
      'long': long,
      'status': status,
    };
  }

  factory SOS.fromMap(Map<String, dynamic> map) {
    return SOS(
      id: map['id'] as int?,
      lat: map['lat'] as double,
      long: map['long'] as double,
      status: map['status'] as String,
    );
  }

  String toJson() => json.encode(toMap());

  factory SOS.fromJson(String source) => SOS.fromMap(json.decode(source) as Map<String, dynamic>);
}