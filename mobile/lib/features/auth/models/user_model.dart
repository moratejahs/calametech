import 'dart:convert';

class UserModel {
  final int id;
  final String name;
  final String phone;
  final String address;
  final String avatar;
  final String email;
  final bool isVerified;
  final String token;

  UserModel({
    required this.id,
    required this.name,
    required this.phone,
    required this.address,
    required this.avatar,
    required this.email,
    required this.isVerified,
    required this.token,
  });

  UserModel copyWith({
    int? id,
    String? name,
    String? phone,
    String? address,
    String? avatar,
    String? email,
    bool? isVerified,
    String? token,
  }) {
    return UserModel(
      id: id ?? this.id,
      name: name ?? this.name,
      phone: phone ?? this.phone,
      address: address ?? this.address,
      avatar: avatar ?? this.avatar,
      email: email ?? this.email,
      isVerified: isVerified ?? this.isVerified,
      token: token ?? this.token,
    );
  }

  Map<String, dynamic> toMap() {
    return <String, dynamic>{
      'id': id,
      'name': name,
      'phone': phone,
      'address': address,
      'avatar': avatar,
      'email': email,
      'isVerified': isVerified,
      'token': token,
    };
  }

  factory UserModel.fromMap(Map<String, dynamic> map) {
    return UserModel(
      id: map['id'] as int,
      name: map['name'] as String,
      phone: map['phone'] as String,
      address: map['address'] as String,
      avatar: map['avatar'] as String,
      email: map['email'] as String,
      isVerified: map['isVerified'] as bool,
      token: map['token'] as String,
    );
  }

  String toJson() => json.encode(toMap());

  factory UserModel.fromJson(String source) => UserModel.fromMap(json.decode(source) as Map<String, dynamic>);

  @override
  String toString() {
    return 'UserModel(id: $id, name: $name, phone: $phone, address: $address, avatar: $avatar, email: $email, isVerified: $isVerified, token: $token)';
  }

  @override
  bool operator ==(covariant UserModel other) {
    if (identical(this, other)) return true;

    return other.id == id &&
        other.name == name &&
        other.phone == phone &&
        other.address == address &&
        other.avatar == avatar &&
        other.email == email &&
        other.isVerified == isVerified &&
        other.token == token;
  }

  @override
  int get hashCode {
    return id.hashCode ^ name.hashCode ^ phone.hashCode ^ address.hashCode ^ avatar.hashCode ^ email.hashCode ^ isVerified.hashCode ^ token.hashCode;
  }
}
