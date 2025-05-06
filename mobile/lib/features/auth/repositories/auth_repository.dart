import 'dart:convert';
import 'dart:io';
import 'package:calamitech/constants/api_paths.dart';
import 'package:calamitech/core/exceptions/validation_exception.dart';
import 'package:calamitech/core/utils/helpers/parse_laravel_validation_errors.dart';
import 'package:calamitech/core/utils/services/auth_user_service.dart';
import 'package:calamitech/features/auth/models/user_model.dart';
import 'package:calamitech/features/auth/repositories/i_auth_repository.dart';
import 'package:http/http.dart' as http;
import 'package:http_parser/http_parser.dart';

class AuthRepository implements IAuthRepository {
  final http.Client httpClient;
  final AuthUserService authUserService;

  const AuthRepository({
    required this.httpClient,
    required this.authUserService,
  });

  @override
  Future<UserModel> login(String email, String password) async {
    final response = await httpClient.post(
      Uri.parse(ApiPaths.login),
      headers: {
        'Accept': 'application/json',
      },
      body: {
        'email': email,
        'password': password,
      },
    );

    final jsonBody = json.decode(response.body);

    if (response.statusCode == 200) {
      return UserModel.fromMap({
        'id': jsonBody['user']['id'],
        'name': jsonBody['user']['name'],
        'email': jsonBody['user']['email'],
        'phone': jsonBody['user']['phone'],
        'address': jsonBody['user']['address'],
        'avatar': jsonBody['user']['avatar'],
        'isVerified': jsonBody['user']['is_verified'],
        'token': jsonBody['token'],
      });
    } else if (response.statusCode == 422) {
      throw ValidationException(parseLaravelValidationErrors(jsonBody['errors']));
    } else {
      throw Exception(jsonBody['message'] ?? 'Failed to login.');
    }
  }

  @override
  Future<UserModel> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    required String phone,
    required String address,
    required File avatar,
    required File idPicture,
    required String idType,
  }) async {
    var request = http.MultipartRequest(
      'POST',
      Uri.parse(ApiPaths.register),
    );

    // Add headers
    request.headers.addAll({
      'Accept': 'application/json',
    });

    // Add form fields
    request.fields['name'] = name;
    request.fields['address'] = address;
    request.fields['phone'] = phone;
    request.fields['email'] = email;
    request.fields['password'] = password;
    request.fields['password_confirmation'] = passwordConfirmation;
    request.fields['id_type'] = idType;

    final avatarFileName = avatar.path.split('/').last;
    final avatarBytes = await avatar.readAsBytes();
    final avatarFile = http.MultipartFile.fromBytes(
      'avatar',
      avatarBytes,
      filename: avatarFileName,
      contentType: getContentType(avatarFileName),
    );
    request.files.add(avatarFile);

    final idPictureFileName = idPicture.path.split('/').last;
    final idPictureBytes = await idPicture.readAsBytes();
    final idPictureFile = http.MultipartFile.fromBytes(
      'id_picture',
      idPictureBytes,
      filename: idPictureFileName,
      contentType: getContentType(idPictureFileName),
    );
    request.files.add(idPictureFile);

    // Send request
    final streamedResponse = await request.send();
    final response = await http.Response.fromStream(streamedResponse);
    final jsonBody = jsonDecode(response.body);

    if (response.statusCode == 201) {
      return UserModel.fromMap({
        'id': jsonBody['user']['id'] as int,
        'name': jsonBody['user']['name'] as String,
        'address': jsonBody['user']['address'] as String,
        'phone': jsonBody['user']['phone'] as String,
        'avatar': jsonBody['user']['avatar'] as String,
        'email': jsonBody['user']['email'] as String,
        'isVerified': jsonBody['user']['is_verified'] as bool,
        'token': jsonBody['token'] as String,
      });
    } else if (response.statusCode == 422) {
      throw ValidationException(parseLaravelValidationErrors(jsonBody['errors']));
    } else {
      throw Exception(jsonBody['message'] ?? 'Failed to submit report.');
    }
  }

  @override
  Future<void> logout() async {
    final user = await authUserService.get();

    if (user == null) {
      throw Exception('Unauthenticated.');
    }

    final response = await httpClient.post(
      Uri.parse(ApiPaths.logout),
      headers: {'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': 'Bearer ${user.token}'},
    );

    final jsonBody = json.decode(response.body);

    if (response.statusCode == 200) {
      if (!await authUserService.delete()) {
        throw Exception('Failed to delete user data.');
      }
    } else {
      throw Exception(jsonBody['message'] ?? 'Failed to logout.');
    }
  }
}

MediaType getContentType(String fileName) {
  final extension = fileName.split('.').last.toLowerCase();
  switch (extension) {
    case 'jpg':
    case 'jpeg':
      return MediaType('image', 'jpeg');
    case 'png':
      return MediaType('image', 'png');
    case 'gif':
      return MediaType('image', 'gif');
    default:
      return MediaType('application', 'octet-stream');
  }
}
