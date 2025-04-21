Map<String, List<String>> parseLaravelValidationErrors(Map<String, dynamic> errorsJson) {
  return Map<String, List<String>>.from(
      errorsJson.map((key, value) {
        return MapEntry(
            key,
            List<String>.from(value.map((e) => e.toString()))
        );
      })
  );
}