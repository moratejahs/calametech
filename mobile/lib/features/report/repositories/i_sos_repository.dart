import 'dart:io';

import 'package:calamitech/features/report/models/sos_model.dart';

abstract class ISosRepository {
  Future<void> store(
    String description,
    String type,
    File? image,
    String lat,
    String long,
  );
  Future<SosModel?> getFromStorage();
  Future<bool> storeInStorage(
      SosModel sos,
      );
}
