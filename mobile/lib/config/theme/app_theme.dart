import 'package:flutter/material.dart';
import 'package:hexcolor/hexcolor.dart';

class AppTheme {
  static Color primaryColor = HexColor('#08BFF1');

  OutlineInputBorder _buildOutlineInputBorder(Color color) {
    return OutlineInputBorder(
      borderRadius: BorderRadius.circular(6),
      borderSide: BorderSide(
        color: color,
        width: 2,
      ),
    );
  }

  static ThemeData lightTheme = ThemeData(
    useMaterial3: true,
    colorScheme: const ColorScheme.light().copyWith(
      primary: AppTheme.primaryColor,
    ),
    inputDecorationTheme: InputDecorationTheme(
      fillColor: Colors.white,
      filled: true,
      floatingLabelBehavior: FloatingLabelBehavior.never,
      enabledBorder: AppTheme()._buildOutlineInputBorder(Colors.grey[300]!),
      focusedBorder: AppTheme()._buildOutlineInputBorder(AppTheme.primaryColor),
      errorBorder: AppTheme()._buildOutlineInputBorder(Colors.red),
      focusedErrorBorder: AppTheme()._buildOutlineInputBorder(Colors.red),
      hintStyle: TextStyle(
        color: Colors.grey[300],
        fontSize: 14,
      ),
    ),
    elevatedButtonTheme: ElevatedButtonThemeData(
      style: ButtonStyle(
        minimumSize: WidgetStateProperty.all(
          const Size(double.infinity, 55),
        ),
        backgroundColor: WidgetStateProperty.resolveWith<Color>(
          (_) => AppTheme.primaryColor,
        ),
        shape: WidgetStateProperty.resolveWith<OutlinedBorder>(
          (_) => RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(10),
          ),
        ),
        textStyle: WidgetStateProperty.resolveWith(
          (_) => const TextStyle(
            fontSize: 16,
          ),
        ),
        foregroundColor: WidgetStateProperty.all<Color>(Colors.white),
      ),
    ),
  );
}
