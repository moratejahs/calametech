// import 'dart:io';
// import 'package:calamitech/constants/asset_paths.dart';
// import 'package:calamitech/features/report/report.dart';
// import 'package:calamitech/features/report/widgets/emergency_type_button.dart';
// import 'package:flutter/material.dart';
// import 'package:flutter_bloc/flutter_bloc.dart';
// import 'package:image_picker/image_picker.dart';
//
// class ReportForm extends StatefulWidget {
//   const ReportForm({super.key});
//
//   @override
//   State<ReportForm> createState() => _ReportFormState();
// }
//
// class _ReportFormState extends State<ReportForm> {
//   String? selectedEmergencyType;
//   final TextEditingController _descriptionController = TextEditingController();
//   File? _imageFile;
//   final ImagePicker _picker = ImagePicker();
//
//   @override
//   void dispose() {
//     _descriptionController.dispose();
//     super.dispose();
//   }
//
//   void _selectEmergencyType(String type) {
//     setState(() {
//       selectedEmergencyType = type;
//     });
//   }
//
//   Future<void> _pickImage() async {
//     final pickedFile = await _picker.pickImage(source: ImageSource.gallery);
//     if (pickedFile != null) {
//       setState(() {
//         _imageFile = File(pickedFile.path);
//       });
//     }
//   }
//
//   @override
//   Widget build(BuildContext context) {
//     return BlocListener<ReportBloc, ReportState>(
//       listener: (context, state) {
//         if (state is ReportSuccess) {
//           ScaffoldMessenger.of(context).showSnackBar(
//             SnackBar(
//               content: Text(state.message),
//               backgroundColor: Colors.green,
//               duration: const Duration(seconds: 5),
//               behavior: SnackBarBehavior.floating,
//             ),
//           );
//
//           setState(() {
//             selectedEmergencyType = null;
//             _descriptionController.clear();
//             _imageFile = null;
//           });
//         }
//
//         if (state is ReportError) {
//           ScaffoldMessenger.of(context).showSnackBar(
//             SnackBar(
//               content: Text(state.message ?? 'Failed to submit report.'),
//               backgroundColor: Colors.red,
//               behavior: SnackBarBehavior.floating,
//             ),
//           );
//         }
//       },
//       child: Padding(
//         padding: const EdgeInsets.all(16.0),
//         child: SingleChildScrollView(
//           child: Column(
//             spacing: 16.0,
//             mainAxisAlignment: MainAxisAlignment.start,
//             crossAxisAlignment: CrossAxisAlignment.start,
//             children: [
//               const Text(
//                 'What kind of Emergency?',
//                 style: TextStyle(color: Colors.red, fontSize: 18.0),
//               ),
//               Row(
//                 mainAxisAlignment: MainAxisAlignment.center,
//                 crossAxisAlignment: CrossAxisAlignment.center,
//                 children: [
//                   EmergencyTypeButton(
//                     image: AssetPaths.fire,
//                     title: 'Fire',
//                     isSelected: selectedEmergencyType == 'fire',
//                     onTap: () => _selectEmergencyType('fire'),
//                   ),
//                   const SizedBox(width: 16.0),
//                   EmergencyTypeButton(
//                     image: AssetPaths.home,
//                     title: 'Flood',
//                     isSelected: selectedEmergencyType == 'flood',
//                     onTap: () => _selectEmergencyType('flood'),
//                   ),
//                 ],
//               ),
//               Column(
//                 mainAxisAlignment: MainAxisAlignment.start,
//                 crossAxisAlignment: CrossAxisAlignment.start,
//                 children: [
//                   const Text(
//                     'Describe the situation',
//                     style: TextStyle(color: Colors.red, fontSize: 18.0),
//                   ),
//                   const SizedBox(height: 8.0),
//                   TextField(
//                     controller: _descriptionController,
//                     maxLines: 5,
//                     decoration: const InputDecoration(
//                       hintText: 'Type here...',
//                       contentPadding: EdgeInsets.all(16.0),
//                       border: InputBorder.none,
//                     ),
//                   ),
//                 ],
//               ),
//               Column(
//                 mainAxisAlignment: MainAxisAlignment.start,
//                 crossAxisAlignment: CrossAxisAlignment.start,
//                 children: [
//                   const Text(
//                     'Upload an image',
//                     style: TextStyle(color: Colors.red, fontSize: 18.0),
//                   ),
//                   const SizedBox(height: 8.0),
//                   GestureDetector(
//                     onTap: _pickImage,
//                     child: Container(
//                       width: double.infinity,
//                       height: 250.0,
//                       decoration: BoxDecoration(
//                         borderRadius: BorderRadius.circular(8.0),
//                         border: Border.all(color: Colors.grey.shade300, width: 1.0),
//                       ),
//                       child: _imageFile != null
//                           ? Image.file(
//                               _imageFile!,
//                               width: 120.0,
//                               height: 120.0,
//                               fit: BoxFit.cover,
//                             )
//                           : const Icon(
//                               Icons.camera_alt,
//                               size: 40.0,
//                               color: Colors.grey,
//                             ),
//                     ),
//                   ),
//                 ],
//               ),
//               ElevatedButton(
//                 style: ButtonStyle(
//                   backgroundColor: WidgetStateProperty.all<Color>(Colors.red),
//                 ),
//                 onPressed: () {
//                   if (selectedEmergencyType != null && _descriptionController.text.isNotEmpty) {
//                     context.read<ReportBloc>().add(
//                           ReportSubmitted(
//                             emergencyType: selectedEmergencyType!,
//                             description: _descriptionController.text,
//                             image: _imageFile,
//                           ),
//                         );
//                   }
//
//                   if (selectedEmergencyType == null) {
//                     ScaffoldMessenger.of(context).showSnackBar(
//                       const SnackBar(
//                         content: Text('Please select an emergency type.'),
//                         backgroundColor: Colors.red,
//                         behavior: SnackBarBehavior.floating,
//                       ),
//                     );
//                   }
//                   if (_descriptionController.text.isEmpty) {
//                     ScaffoldMessenger.of(context).showSnackBar(
//                       const SnackBar(
//                         content: Text('Please describe the situation.'),
//                         backgroundColor: Colors.red,
//                         behavior: SnackBarBehavior.floating,
//                       ),
//                     );
//                   }
//                 },
//                 child: BlocBuilder<ReportBloc, ReportState>(
//                   builder: (context, state) {
//                     if (state is ReportLoading) {
//                       return const CircularProgressIndicator();
//                     }
//
//                     return const Text('Submit Report');
//                   },
//                 ),
//               ),
//             ],
//           ),
//         ),
//       ),
//     );
//   }
// }
